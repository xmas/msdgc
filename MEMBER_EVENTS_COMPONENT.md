# MemberEvents Component

A Vue.js component that displays all events a member is part of, including event details, join dates, and custom attributes.

## Features

- **Event Listing**: Shows all events a member is participating in
- **Event Details**: Displays event name, group, and join date
- **Custom Attributes**: Expandable view for member-specific event attributes
- **Loading States**: Provides visual feedback during data fetching
- **Error Handling**: Graceful error handling with user-friendly messages
- **Responsive Design**: Works well on all screen sizes
- **Dark Mode Support**: Adapts to light/dark theme preferences
- **Refresh Functionality**: Manual refresh button for up-to-date information

## Usage

### Basic Usage
```vue
<template>
  <MemberEvents :userId="123" />
</template>

<script setup>
import MemberEvents from '@/Components/MemberEvents.vue';
</script>
```

### With Template Ref (for manual control)
```vue
<template>
  <MemberEvents ref="memberEventsRef" :userId="selectedUserId" />
  <button @click="refreshEvents">Refresh Events</button>
</template>

<script setup>
import { ref } from 'vue';
import MemberEvents from '@/Components/MemberEvents.vue';

const memberEventsRef = ref(null);
const selectedUserId = ref(123);

const refreshEvents = () => {
  memberEventsRef.value?.fetchEvents();
};
</script>
```

## Props

| Prop | Type | Required | Description |
|------|------|----------|-------------|
| `userId` | `Number\|String` | Yes | The ID of the user whose events to display |

## API Requirements

The component expects the following API endpoint to be available:
- `GET /api/users/{userId}/events` - Returns events for a specific user

### Expected API Response Format
```json
[
  {
    "id": 1,
    "name": "Event Name",
    "event_group": "Event Group",
    "created_at": "2023-01-01T00:00:00Z",
    "pivot": {
      "created_at": "2023-01-01T00:00:00Z",
      "attrs": {
        "custom_field": "custom_value"
      }
    }
  }
]
```

## Styling

The component uses Tailwind CSS classes and supports both light and dark modes. Key styling features:

- **Cards**: Events are displayed in individual cards with hover effects
- **Icons**: Uses Heroicons for consistent iconography
- **Colors**: Supports theme-aware colors (light/dark mode)
- **Typography**: Responsive typography with proper hierarchy
- **Spacing**: Consistent spacing using Tailwind's spacing system

## Events Display

Each event card shows:
1. **Event Name** - Primary heading
2. **Event Group** - Secondary information with icon
3. **Join Date** - When the member joined the event
4. **Custom Attributes** - Expandable section (if attributes exist)
5. **Actions** - Toggle button for attributes

## Empty States

The component handles various states:
- **Loading**: Shows spinner while fetching data
- **Empty**: Displays friendly message when no events are found
- **Error**: Shows error message if API call fails

## Component Methods

### Exposed Methods (via template ref)
- `fetchEvents()` - Manually refresh the events list

## Dependencies

- Vue 3 (Composition API)
- Axios (for API calls)
- Tailwind CSS (for styling)

## Browser Support

The component works in all modern browsers that support Vue 3 and ES6+.

## Example Integration

### In Dashboard
```vue
<template>
  <div class="dashboard-card">
    <h3>My Events</h3>
    <MemberEvents :userId="$attrs.auth.user.id" />
  </div>
</template>
```

### In User Profile
```vue
<template>
  <div class="profile-section">
    <h2>{{ user.name }}'s Events</h2>
    <MemberEvents :userId="user.id" />
  </div>
</template>
```

## Customization

The component can be customized by:
1. Modifying the Tailwind classes
2. Extending the API response format
3. Adding additional event actions
4. Customizing the empty state message
5. Adding event filtering/sorting capabilities

## Related Components

- `Event.php` - Laravel model for events
- `User.php` - Laravel model with events relationship
- `EventUserController.php` - API controller for event-user relationships
