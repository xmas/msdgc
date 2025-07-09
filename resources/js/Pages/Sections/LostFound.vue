<template>
    <div class="box-page">
        <div class="card-header">
            <span class="card-header-icon">
                <img src="/images/exchange.svg" />
            </span>
            <h1 class="card-header-text">Lost and Found</h1>
        </div>

        <p>&nbsp;</p>
        <div class="box-content">

            <h1 class="text-2xl font-bold text-center mb-4">
                Are you missing a disc at our course? Did you find one? Come by the box at the 2nd tee pad.
            </h1>
            <img src="images/lost_found.jpeg" class="w-full block"/>

            <div>
                <div class="club-name text-6xl font-bold text-zinc uppercase pb-8">
                    The Disc Drop
                </div>
                <img src="/images/disc_drop.jpeg" class="w-100" />
            </div>

            <div>
                With such a small course and lots of trees and bushes, discs can get lodged or hidden from plain
                sight.
                We text the owners of any discs that come in our box and can help you get reunited with your shank
                drive. Usually from mid-spring to mid-fall, you can find club members at tee 2 most times from 4pm
                to
                7pm. If you need more assistance, contact us!

                Two easy ways to contact us: email us at <a href="mailto:info@mineralspringsdgc.com"
                    class="text-blue-600 underline">info@mineralspringsdgc.com</a> or jump on our <a
                    href="https://www.facebook.com/groups/250860298866679" target="_blank" rel="noopener"
                    class="text-blue-600 underline">Facebook group</a> to reach out!
            </div>

            <div>
                    <div class="club-name text-6xl font-bold text-zinc uppercase pb-8">
                        Get Your Disc Back
                    </div>
                     <img src="/images/lost_found_sign.jpg" class="w-100" />
                </div>

                <div>
                   There is a slot on the upper righthand corner of the metal box where discs can be deposited when found on the course. This is where you will come to reclaim a disc when you receive a text.
            </div>
        </div>
    </div>
</template>

<script setup>
// No script logic needed for this static page
</script>

<style scoped>
p:hover {
    color: inherit;
    transition: none;
}

ul {
    margin: 1em 0;
    padding-left: 1.5em;
}

li {
    margin-bottom: 0.5em;
    line-height: 1.6;
    list-style-type: disc;
    padding-left: 1.2em;
}

.course-image {
    flex: 1 1 250px;
    display: flex;
    align-items: flex-start;
    justify-content: center;
}

.course-image img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}
</style>
