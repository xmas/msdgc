<template>
  <div class="member-list-container">
    <!-- Header with controls -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Member List</h2>
        <p class="text-gray-600 dark:text-gray-400">{{ members.length }} members</p>
      </div>

      <div class="flex gap-3">
        <!-- Download Sample CSV -->
        <a
          href="/sample-members.csv"
          download="sample-members.csv"
          class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors duration-200"
        >
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          Sample CSV
        </a>

        <!-- CSV Upload -->
        <label class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md cursor-pointer transition-colors duration-200">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
          </svg>
          Upload CSV
          <input
            type="file"
            @change="handleFileUpload"
            accept=".csv"
            class="hidden"
            ref="fileInput"
          />
        </label>

        <!-- Add New Member -->
        <button
          @click="addNewMember"
          class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors duration-200"
        >
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
          Add Member
        </button>

        <!-- Save All Changes -->
        <button
          @click="saveAllChanges"
          :disabled="!hasUnsavedChanges"
          class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white text-sm font-medium rounded-md transition-colors duration-200"
        >
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
          </svg>
          Save All
        </button>

        <!-- Merge Members -->
        <button
          @click="openMergeModal"
          :disabled="!canMerge"
          class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white text-sm font-medium rounded-md transition-colors duration-200"
        >
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
          </svg>
          Merge ({{ selectedMembers.size }})
        </button>
      </div>
    </div>

    <!-- Search and Filter -->
    <div class="mb-4">
      <div class="relative">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search members..."
          class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        />
        <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
      </div>
    </div>

    <!-- Virtual Scroller Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
      <!-- Table Header -->
      <div class="bg-gray-50 dark:bg-gray-700 px-6 py-3 border-b border-gray-200 dark:border-gray-600">
        <div class="grid grid-cols-12 gap-4 text-sm font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
          <div class="col-span-1 flex items-center">
            <input
              type="checkbox"
              @change="toggleAllMembers"
              :checked="selectedMembers.size === members.length && members.length > 0"
              :indeterminate="selectedMembers.size > 0 && selectedMembers.size < members.length"
              class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
            />
          </div>
          <div class="col-span-2">Name</div>
          <div class="col-span-2">Email</div>
          <div class="col-span-1">Phone</div>
          <div class="col-span-1">Tags</div>
          <div class="col-span-1">Provider</div>
          <div class="col-span-2">Paid Via</div>
          <div class="col-span-1">How Heard</div>
          <div class="col-span-1">Actions</div>
        </div>
      </div>

      <!-- Virtual Scroller Content -->
      <div class="relative" style="height: 600px;">
        <RecycleScroller
          class="scroller"
          :items="filteredMembers"
          :item-size="80"
          key-field="id"
          v-slot="{ item, index }"
        >
          <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
            <div class="grid grid-cols-12 gap-4 items-center">
              <!-- Checkbox -->
              <div class="col-span-1">
                <input
                  type="checkbox"
                  :checked="selectedMembers.has(item.id)"
                  @change="toggleMember(item.id)"
                  class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                />
              </div>

              <!-- Name -->
              <div class="col-span-2">
                <input
                  v-model="item.name"
                  @input="markAsChanged(item)"
                  type="text"
                  class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="Full Name"
                />
                <div v-if="item.isNew" class="text-xs text-green-600 dark:text-green-400 mt-1">New</div>
                <div v-else-if="item.hasChanges" class="text-xs text-orange-600 dark:text-orange-400 mt-1">Modified</div>
              </div>

              <!-- Email -->
              <div class="col-span-2">
                <input
                  v-model="item.email"
                  @input="markAsChanged(item)"
                  type="email"
                  class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="email@example.com"
                />
              </div>

              <!-- Phone -->
              <div class="col-span-1">
                <input
                  v-model="item.sms"
                  @input="markAsChanged(item)"
                  type="tel"
                  class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="Phone"
                />
              </div>

              <!-- Tags -->
              <div class="col-span-1">
                <input
                  v-model="item.tags"
                  @input="markAsChanged(item)"
                  type="text"
                  class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="Tags"
                  :title="item.tags"
                />
              </div>

              <!-- Provider -->
              <div class="col-span-1">
                <select
                  v-model="item.provider"
                  @change="markAsChanged(item)"
                  class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                  <option value="">Select</option>
                  <option value="google">Google</option>
                  <option value="facebook">Facebook</option>
                  <option value="paypal">PayPal</option>
                  <option value="cash">Cash</option>
                  <option value="manual">Manual</option>
                </select>
              </div>

              <!-- Paid Via -->
              <div class="col-span-2">
                <input
                  v-model="item.paid_via"
                  @input="markAsChanged(item)"
                  type="text"
                  class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="Payment method"
                />
              </div>

              <!-- How They Heard -->
              <div class="col-span-1">
                <input
                  v-model="item.how_did_you_hear"
                  @input="markAsChanged(item)"
                  type="text"
                  class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="How heard"
                  :title="item.how_did_you_hear"
                />
              </div>

              <!-- Actions -->
              <div class="col-span-1 flex gap-2">
                <button
                  @click="saveMember(item)"
                  v-if="item.hasChanges || item.isNew"
                  class="p-1 text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300"
                  title="Save"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                  </svg>
                </button>
                <button
                  @click="deleteMember(item, index)"
                  class="p-1 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                  title="Delete"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </RecycleScroller>
      </div>
    </div>

    <!-- CSV Upload Modal -->
    <div v-if="showCsvModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-2xl max-h-screen overflow-y-auto">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">CSV Upload Preview</h3>
        <div class="mb-4">
          <p class="text-sm text-gray-600 dark:text-gray-400">
            Found <span class="font-semibold text-green-600">{{ csvData.length }}</span> valid rows to import
          </p>
          <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
            Required columns: Email Address, and either Name or First Name/Last Name. Optional: SMS Phone Number, Provider, Tags
          </p>
        </div>

        <!-- Preview Table -->
        <div class="mb-4 border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden">
          <div class="bg-gray-50 dark:bg-gray-700 px-4 py-2">
            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Preview (first 5 rows)</h4>
          </div>
          <div class="max-h-60 overflow-y-auto">
            <table class="min-w-full text-xs">
              <thead class="bg-gray-100 dark:bg-gray-600">
                <tr>
                  <th class="px-3 py-2 text-left font-medium text-gray-700 dark:text-gray-300">Name</th>
                  <th class="px-3 py-2 text-left font-medium text-gray-700 dark:text-gray-300">Email</th>
                  <th class="px-3 py-2 text-left font-medium text-gray-700 dark:text-gray-300">Phone</th>
                  <th class="px-3 py-2 text-left font-medium text-gray-700 dark:text-gray-300">Provider</th>
                  <th class="px-3 py-2 text-left font-medium text-gray-700 dark:text-gray-300">Paid Via</th>
                  <th class="px-3 py-2 text-left font-medium text-gray-700 dark:text-gray-300">Tags</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(row, index) in csvData.slice(0, 5)" :key="index" class="border-t border-gray-200 dark:border-gray-600">
                  <td class="px-3 py-2 text-gray-900 dark:text-gray-100">{{ row.name }}</td>
                  <td class="px-3 py-2 text-gray-900 dark:text-gray-100">{{ row.email }}</td>
                  <td class="px-3 py-2 text-gray-900 dark:text-gray-100">{{ row.sms }}</td>
                  <td class="px-3 py-2 text-gray-900 dark:text-gray-100">{{ row.provider }}</td>
                  <td class="px-3 py-2 text-gray-900 dark:text-gray-100">{{ row.paid_via }}</td>
                  <td class="px-3 py-2 text-gray-900 dark:text-gray-100 max-w-xs truncate" :title="row.tags">{{ row.tags }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-if="csvData.length > 5" class="px-4 py-2 bg-gray-50 dark:bg-gray-700 text-xs text-gray-500">
            ... and {{ csvData.length - 5 }} more rows
          </div>
        </div>

        <div class="flex gap-3">
          <button
            @click="confirmCsvImport"
            class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors duration-200"
          >
            Import {{ csvData.length }} Members
          </button>
          <button
            @click="cancelCsvImport"
            class="flex-1 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors duration-200"
          >
            Cancel
          </button>
        </div>
      </div>
    </div>

    <!-- Merge Members Modal -->
    <div v-if="showMergeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-4xl max-h-screen overflow-y-auto">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Merge Members</h3>
        <div class="mb-4">
          <p class="text-sm text-gray-600 dark:text-gray-400">
            Merging <span class="font-semibold text-orange-600">{{ selectedMembersList.length }}</span> members into ID: {{ Math.min(...selectedMembersList.map(m => m.id)) }}
          </p>
          <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
            Select which value to use for each field from the members being merged. Tags will be automatically combined.
          </p>
        </div>

        <!-- Preview of merged member -->
        <div class="mb-4 border border-blue-200 dark:border-blue-600 rounded-lg overflow-hidden bg-blue-50 dark:bg-blue-900/20">
          <div class="bg-blue-100 dark:bg-blue-800 px-4 py-2">
            <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200">Preview of Merged Member</h4>
          </div>
          <div class="p-4 space-y-2 text-sm">
            <div><strong>Name:</strong> {{ previewMergedMember.name || '(empty)' }}</div>
            <div><strong>Email:</strong> {{ previewMergedMember.email || '(empty)' }}</div>
            <div><strong>Phone:</strong> {{ previewMergedMember.sms || '(empty)' }}</div>
            <div><strong>Provider:</strong> {{ previewMergedMember.provider || '(empty)' }}</div>
            <div><strong>Paid Via:</strong> {{ previewMergedMember.paid_via || '(empty)' }}</div>
            <div><strong>How Heard:</strong> {{ previewMergedMember.how_did_you_hear || '(empty)' }}</div>
            <div><strong>Tags:</strong> {{ previewMergedMember.tags || '(no tags)' }}</div>
            <div class="text-xs text-gray-500 mt-2">
              Debug - Selected members: {{ selectedMembersList.length }},
              Raw tags: {{ selectedMembersList.map(m => m.tags).join(' | ') }}
            </div>
          </div>
        </div>

        <!-- Merge Fields Table -->
        <div class="mb-4 border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden">
          <div class="bg-gray-50 dark:bg-gray-700 px-4 py-2">
            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Field Selection</h4>
          </div>
          <div class="max-h-96 overflow-y-auto">
            <table class="min-w-full text-sm">
              <thead class="bg-gray-100 dark:bg-gray-600 sticky top-0">
                <tr>
                  <th class="px-3 py-2 text-left font-medium text-gray-700 dark:text-gray-300">Field</th>
                  <th v-for="member in selectedMembersList" :key="member.id" class="px-3 py-2 text-left font-medium text-gray-700 dark:text-gray-300">
                    ID: {{ member.id }}<br>
                    <span class="text-xs font-normal">{{ member.name || 'No Name' }}</span>
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="field in ['name', 'email', 'sms', 'provider', 'tags', 'paid_via', 'how_did_you_hear']" :key="field" class="border-t border-gray-200 dark:border-gray-600">
                  <td class="px-3 py-2 font-medium text-gray-900 dark:text-gray-100 capitalize">{{ field.replace('_', ' ') }}</td>
                  <td v-if="field === 'tags'" colspan="100" class="px-3 py-2 text-center text-sm text-gray-600 dark:text-gray-400 italic">
                    All tags from selected members will be combined automatically
                  </td>
                  <td v-else v-for="member in selectedMembersList" :key="member.id" class="px-3 py-2">
                    <label class="flex items-center space-x-2">
                      <input
                        type="radio"
                        :name="`merge-${field}`"
                        :value="member.id"
                        v-model="mergeFields[field]"
                        class="text-orange-600 focus:ring-orange-500"
                      />
                      <span class="text-gray-900 dark:text-gray-100 truncate max-w-xs" :title="member[field]">
                        {{ member[field] || '(empty)' }}
                      </span>
                    </label>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="flex gap-3">
          <button
            @click="confirmMerge"
            class="flex-1 px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-md transition-colors duration-200"
          >
            Merge {{ selectedMembersList.length }} Members
          </button>
          <button
            @click="cancelMerge"
            class="flex-1 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors duration-200"
          >
            Cancel
          </button>
        </div>
      </div>
    </div>

    <!-- Loading Overlay -->
    <div v-if="loading" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-gray-800 rounded-lg p-6">
        <div class="flex items-center">
          <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <span class="text-gray-900 dark:text-white">{{ loadingMessage }}</span>
        </div>
      </div>
    </div>

    <!-- Notifications -->
    <div class="fixed top-4 right-4 z-50 space-y-2">
      <div
        v-for="notification in notifications"
        :key="notification.id"
        :class="[
          'px-4 py-3 rounded-lg shadow-lg text-white font-medium transform transition-all duration-300',
          {
            'bg-green-600': notification.type === 'success',
            'bg-red-600': notification.type === 'error',
            'bg-yellow-600': notification.type === 'warning',
            'bg-blue-600': notification.type === 'info'
          }
        ]"
      >
        {{ notification.message }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { RecycleScroller } from 'vue3-virtual-scroller'
import Papa from 'papaparse'
import axios from 'axios'

// Configure axios defaults
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
axios.defaults.withCredentials = true;

// Props (if any)
defineProps({
  user: Object,
})

// Reactive data
const members = ref([])
const searchQuery = ref('')
const loading = ref(false)
const loadingMessage = ref('')
const showCsvModal = ref(false)
const csvData = ref([])
const fileInput = ref(null)
const nextId = ref(1)
const selectedMembers = ref(new Set())
const showMergeModal = ref(false)
const mergeFields = ref({})

// Computed properties
const filteredMembers = computed(() => {
  if (!searchQuery.value) return members.value

  const query = searchQuery.value.toLowerCase()
  return members.value.filter(member =>
    member.name?.toLowerCase().includes(query) ||
    member.email?.toLowerCase().includes(query)
  )
})

const hasUnsavedChanges = computed(() => {
  return members.value.some(member => member.hasChanges || member.isNew)
})

const selectedMembersList = computed(() => {
  return members.value.filter(member => selectedMembers.value.has(member.id))
})

const canMerge = computed(() => {
  return selectedMembersList.value.length >= 2
})

const previewMergedMember = computed(() => {
  if (!showMergeModal.value || selectedMembersList.value.length === 0) {
    return {}
  }

  const sortedMembers = selectedMembersList.value.sort((a, b) => a.id - b.id)
  const preview = {}

  // Always compute tags by merging all tags from all selected members
  const allTags = new Set()
  sortedMembers.forEach(member => {
    if (member.tags) {
      if (Array.isArray(member.tags)) {
        // If tags is an array, iterate over it directly
        member.tags.forEach(tag => {
          if (tag && typeof tag === 'string' && tag.trim() !== '') {
            allTags.add(tag.trim())
          }
        })
      } else if (typeof member.tags === 'string' && member.tags.trim() !== '') {
        // If tags is a string, split it by commas
        const memberTags = member.tags.split(',').map(tag => tag.trim()).filter(tag => tag !== '')
        memberTags.forEach(tag => allTags.add(tag))
      }
    }
  })
  preview.tags = Array.from(allTags).join(', ')

  // Generate preview for other fields based on selections
  for (const [field, selectedMemberId] of Object.entries(mergeFields.value)) {
    if (field !== 'tags') { // Skip tags as we handled them above
      const sourceMember = sortedMembers.find(m => m.id === selectedMemberId)
      if (sourceMember) {
        preview[field] = sourceMember[field]
      }
    }
  }

  return preview
})

// Methods
const loadMembers = async () => {
  loading.value = true
  loadingMessage.value = 'Loading members...'

  try {
    // Ensure CSRF cookie is set for session-based authentication
    await axios.get('/sanctum/csrf-cookie')

    const response = await axios.get('/api/members')
    members.value = response.data.map(member => ({
      ...member,
      hasChanges: false,
      isNew: false,
      originalData: { ...member }
    }))

    // Set next ID for new members
    if (members.value.length > 0) {
      nextId.value = Math.max(...members.value.map(m => m.id || 0)) + 1
    }
  } catch (error) {
    console.error('Error loading members:', error)
    showNotification('Error loading members: ' + (error.response?.data?.message || error.message), 'error')
    members.value = []
  } finally {
    loading.value = false
  }
}

const markAsChanged = (member) => {
  if (!member.isNew) {
    member.hasChanges = true
  }
}

const addNewMember = () => {
  const newMember = {
    id: nextId.value++,
    name: '',
    email: '',
    sms: '',
    provider: 'manual',
    tags: 'new',
    paid_via: '',
    how_did_you_hear: '',
    sms_opt_in: true,
    email_opt_in: true,
    hasChanges: false,
    isNew: true,
    originalData: {}
  }

  members.value.unshift(newMember)
}

const saveMember = async (member) => {
  loading.value = true
  loadingMessage.value = member.isNew ? 'Creating member...' : 'Updating member...'

  try {
    const memberData = {
      name: member.name,
      email: member.email,
      sms: member.sms,
      provider: member.provider,
      tags: member.tags,
      sms_opt_in: member.sms_opt_in ?? true,
      email_opt_in: member.email_opt_in ?? true,
      how_did_you_hear: member.how_did_you_hear || '',
      paid_via: member.paid_via || ''
    }

    if (member.isNew) {
      const response = await axios.post('/api/members', memberData)

      // Update the member with the response data
      Object.assign(member, response.data, {
        hasChanges: false,
        isNew: false,
        originalData: { ...response.data }
      })
    } else {
      const response = await axios.put(`/api/members/${member.id}`, memberData)

      member.hasChanges = false
      member.originalData = { ...member }
    }

    // Show success message
    showNotification('Member saved successfully!', 'success')
  } catch (error) {
    console.error('Error saving member:', error)
    const errorMessage = error.response?.data?.message || error.response?.data?.errors || error.message
    showNotification('Error saving member: ' + (typeof errorMessage === 'object' ? JSON.stringify(errorMessage) : errorMessage), 'error')
  } finally {
    loading.value = false
  }
}

const deleteMember = async (member, index) => {
  if (!confirm('Are you sure you want to delete this member?')) return

  loading.value = true
  loadingMessage.value = 'Deleting member...'

  try {
    if (!member.isNew) {
      await axios.delete(`/api/members/${member.id}`)
    }

    members.value.splice(members.value.indexOf(member), 1)
    showNotification('Member deleted successfully!', 'success')
  } catch (error) {
    console.error('Error deleting member:', error)
    showNotification('Error deleting member: ' + (error.response?.data?.message || error.message), 'error')
  } finally {
    loading.value = false
  }
}

const saveAllChanges = async () => {
  const changedMembers = members.value.filter(member => member.hasChanges || member.isNew)
  let saved = 0
  let errors = 0

  for (const member of changedMembers) {
    try {
      await saveMember(member)
      saved++
    } catch (error) {
      errors++
    }
  }

  showNotification(`Saved ${saved} members${errors > 0 ? `, ${errors} errors` : ''}`, errors > 0 ? 'warning' : 'success')
}

const handleFileUpload = (event) => {
  const file = event.target.files[0]
  if (!file) return

  loading.value = true
  loadingMessage.value = 'Parsing CSV file...'

  Papa.parse(file, {
    header: true,
    skipEmptyLines: true,
    complete: (results) => {
      csvData.value = results.data.filter(row => {
        // Check for email address (required field)
        const email = row['Email Address'] || row.email || row.Email
        if (!email || email.trim() === '') return false

        // Check for name (either existing name field or first/last name combination)
        const firstName = (row['First Name'] || row.firstName || row.first_name || '').trim()
        const lastName = (row['Last Name'] || row.lastName || row.last_name || '').trim()
        const existingName = (row.name || row.Name || '').trim()
        const hasName = existingName || firstName || lastName

        return hasName
      }).map(row => {
        // Extract and combine first and last name
        const firstName = (row['First Name'] || row.firstName || row.first_name || '').trim()
        const lastName = (row['Last Name'] || row.lastName || row.last_name || '').trim()
        const existingName = (row.name || row.Name || '').trim()

        // Build full name with fallbacks
        let fullName = existingName
        if (!fullName && (firstName || lastName)) {
          fullName = `${firstName} ${lastName}`.trim()
        }
        if (!fullName) {
          // Use email prefix as fallback name
          const email = row['Email Address'] || row.email || row.Email || ''
          fullName = email.split('@')[0] || 'Unknown Member'
        }

        // Extract phone number from multiple possible columns
        const smsPhone = row['SMS Phone Number'] || ''
        const phoneNumber = row['Phone number'] || row.phone || row.Phone || ''
        const phone = (smsPhone || phoneNumber || '').replace(/[^\d+\-\(\)\s]/g, '').trim()

        // Extract payment provider
        const paidVia = row['Paid $35 via...'] || row['Paid via'] || row.provider || row.Provider || ''
        let provider = 'manual'
        if (paidVia) {
          const paidLower = paidVia.toLowerCase()
          if (paidLower.includes('paypal')) provider = 'paypal'
          else if (paidLower.includes('cash')) provider = 'cash'
          else if (paidLower.includes('google')) provider = 'google'
          else if (paidLower.includes('facebook')) provider = 'facebook'
          else provider = 'manual'
        }

        // Extract tags and additional info
        let tags = []

        // Add existing tags
        if (row.TAGS || row.tags || row.Tags) {
          const existingTags = (row.TAGS || row.tags || row.Tags).split(',').map(tag =>
            tag.replace(/"/g, '').trim()
          ).filter(tag => tag !== '')
          tags.push(...existingTags)
        }

        // Add member year tags
        if (tags.some(tag => tag.includes('2024 Member'))) {
          tags.push('2024')
        }
        if (tags.some(tag => tag.includes('2025 Member'))) {
          tags.push('2025')
        }

        // Add interests from "Select the topics that interest you" field
        const interests = row['Select the topics that interest you.'] || ''
        if (interests) {
          const interestTags = interests.split(',').map(interest => interest.trim()).filter(i => i)
          tags.push(...interestTags)
        }

        // Add how they heard about the club
        const howHeard = row['How did you hear about our club?'] || ''
        if (howHeard && howHeard.trim() !== '') {
          tags.push(`heard-via:${howHeard.toLowerCase()}`)
        }

        // Add tag number if available
        const tagNumber = row['Tag #'] || ''
        if (tagNumber) {
          tags.push(`tag-${tagNumber}`)
        }

        // Add imported tag
        tags.push('imported')

        // Remove duplicates and clean up tags
        tags = [...new Set(tags)].filter(tag => tag && tag.trim() !== '')

        return {
          name: fullName,
          email: row['Email Address'] || row.email || row.Email || '',
          sms: phone,
          provider: provider,
          tags: tags.join(', '),
          sms_opt_in: row.SMS_MARKETING_STATUS !== 'Non-subscribed',
          email_opt_in: true,
          how_did_you_hear: howHeard,
          paid_via: paidVia
        }
      })

      if (csvData.value.length === 0) {
        showNotification('No valid member data found in CSV. Please ensure your CSV has "Email Address" and either "First Name"/"Last Name" or "Name" columns.', 'error')
        loading.value = false
        return
      }

      showCsvModal.value = true
      loading.value = false
    },
    error: (error) => {
      console.error('CSV parsing error:', error)
      loading.value = false
      showNotification('Error parsing CSV file. Please check the format.', 'error')
    }
  })

  // Reset file input
  event.target.value = ''
}

const confirmCsvImport = async () => {
  loading.value = true
  loadingMessage.value = 'Importing members...'

  try {
    const response = await axios.post('/api/members/bulk-import', {
      members: csvData.value
    })

    // Add successful imports to the list
    response.data.members.forEach(memberData => {
      const newMember = {
        ...memberData,
        hasChanges: false,
        isNew: false,
        originalData: { ...memberData }
      }
      members.value.push(newMember)
    })

    showNotification(`Successfully imported ${response.data.created} members${response.data.errors.length > 0 ? `, ${response.data.errors.length} errors` : ''}`, 'success')

    if (response.data.errors.length > 0) {
      console.log('Import errors:', response.data.errors)
    }
  } catch (error) {
    console.error('Bulk import error:', error)
    showNotification('Error importing members: ' + (error.response?.data?.message || error.message), 'error')
  } finally {
    loading.value = false
    showCsvModal.value = false
    csvData.value = []
  }
}

const cancelCsvImport = () => {
  showCsvModal.value = false
  csvData.value = []
}

// Merge functionality
const toggleMember = (memberId) => {
  if (selectedMembers.value.has(memberId)) {
    selectedMembers.value.delete(memberId)
  } else {
    selectedMembers.value.add(memberId)
  }
}

const toggleAllMembers = () => {
  if (selectedMembers.value.size === members.value.length) {
    selectedMembers.value.clear()
  } else {
    selectedMembers.value = new Set(members.value.map(m => m.id))
  }
}

const openMergeModal = () => {
  if (!canMerge.value) return

  // Initialize merge fields with the first member's values as defaults
  const sortedMembers = selectedMembersList.value.sort((a, b) => a.id - b.id)
  const firstMember = sortedMembers[0]

  mergeFields.value = {
    name: firstMember.id,
    email: firstMember.id,
    sms: firstMember.id,
    provider: firstMember.id,
    tags: firstMember.id,
    paid_via: firstMember.id,
    how_did_you_hear: firstMember.id
  }

  showMergeModal.value = true
}

const confirmMerge = async () => {
  loading.value = true
  loadingMessage.value = 'Merging members...'

  try {
    const sortedMembers = selectedMembersList.value.sort((a, b) => a.id - b.id)
    const targetMember = sortedMembers[0] // Member with lowest ID
    const membersToDelete = sortedMembers.slice(1)

    // Build the merged member data
    const mergedData = {}

    // Handle tags separately - always merge all tags
    const allTags = new Set()
    sortedMembers.forEach(member => {
      if (member.tags) {
        if (Array.isArray(member.tags)) {
          // If tags is an array, iterate over it directly
          member.tags.forEach(tag => {
            if (tag && typeof tag === 'string' && tag.trim() !== '') {
              allTags.add(tag.trim())
            }
          })
        } else if (typeof member.tags === 'string' && member.tags.trim() !== '') {
          // If tags is a string, split it by commas
          const memberTags = member.tags.split(',').map(tag => tag.trim()).filter(tag => tag !== '')
          memberTags.forEach(tag => allTags.add(tag))
        }
      }
    })
    mergedData.tags = Array.from(allTags).join(', ')

    // Handle other fields based on user selection
    for (const [field, selectedMemberId] of Object.entries(mergeFields.value)) {
      if (field !== 'tags') { // Skip tags as we handled them above
        const sourceMember = sortedMembers.find(m => m.id === selectedMemberId)
        if (sourceMember) {
          mergedData[field] = sourceMember[field]
        }
      }
    }

    // Update the target member with merged data
    Object.assign(targetMember, mergedData)
    markAsChanged(targetMember)

    // Save the updated member
    await saveMember(targetMember)

    // Delete the other members
    for (const member of membersToDelete) {
      if (!member.isNew) {
        await axios.delete(`/api/members/${member.id}`)
      }
      const index = members.value.indexOf(member)
      if (index > -1) {
        members.value.splice(index, 1)
      }
    }

    // Clear selection and close modal
    selectedMembers.value.clear()
    showMergeModal.value = false

    showNotification(`Successfully merged ${sortedMembers.length} members into ID: ${targetMember.id}`, 'success')
  } catch (error) {
    console.error('Merge error:', error)
    showNotification('Error merging members: ' + (error.response?.data?.message || error.message), 'error')
  } finally {
    loading.value = false
  }
}

const cancelMerge = () => {
  showMergeModal.value = false
  mergeFields.value = {}
}

// Simple notification system
const notifications = ref([])

const showNotification = (message, type = 'info') => {
  const id = Date.now()
  notifications.value.push({ id, message, type })

  setTimeout(() => {
    notifications.value = notifications.value.filter(n => n.id !== id)
  }, 5000)
}

// Lifecycle
onMounted(() => {
  loadMembers()
})
</script>

<style scoped>
.member-list-container {
  max-width: 100%;
  margin: 0 auto;
  padding: 1.5rem;
}

.scroller {
  height: 100%;
}

/* Virtual scroller styles */
.vue-recycle-scroller {
  position: relative;
}

.vue-recycle-scroller.direction-vertical .vue-recycle-scroller__slot {
  display: flex;
  flex-direction: column;
}

.vue-recycle-scroller__item-wrapper {
  flex: none;
  overflow: hidden;
}

.vue-recycle-scroller__item-view {
  width: 100%;
}
</style>
