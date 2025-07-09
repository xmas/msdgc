<template>
    <div class="box-page">
        <div class="card-header">
            <span class="card-header-icon">
                <img src="/images/basket.svg"/>
            </span>
            <h1 class="card-header-text">The Course</h1>
        </div>

        <p>&nbsp;</p>
        <div class="box-content flex-col">
            <div class="relative flex w-full flex-1 items-stretch"> <a href="images/north_park_map.jpg" target="_blank"
                    rel="noopener">
                    <img src="images/north_park_map.jpg" alt="Mineral Springs Disc Golf Course Map" />
                </a>
            </div>
            <div class="box-text">
                <p>Mineral Springs Disc Golf Course was designed by Lowell Shields (PDGA #687) in 1987 with an
                    impressive use of limited space.</p>
                <p>&nbsp;</p>

                <p>Located on a very small 2.5 acre park, this course is heavily wooded and has tight fairways that call
                    for accurate throws and distance control. It’s an 18 hole layout with 9 fairways and 14 baskets
                    (8holes share 4 baskets). There is a tee sign at each set of tee pads and a course map (pictured to
                    the right) by the 1st tee pad on the SE corner of the property.</p>
                <p>&nbsp;</p>

                <p>We are in the process of updating our tee signs and adding additional navigational signs for ease of
                    play, but here are a few tips if you’re visiting for the first time:</p>
                <ul>
                    <li>Download the UDisc app and take advantage of the free account features including GPS course map.
                    </li>
                    <li><a href="https://udisc.com/courses/" target="_blank" rel="noopener">Click here for UDisc
                            website</a></li>
                    <li>Look closely at each tee sign for important things like OB’s, mando’s, and the blue (front 9)
                        and red (back 9) baskets.</li>
                    <li>Use the arrows attached to the underside of each basket to point you toward the next set of
                        tee-pads.</li>
                </ul>
                <p>It is a multi-use park so please be courteous of those around you and watch for flying discs at all
                    times.</p>
                <p>&nbsp;</p>

                <p>This park is volunteer-run and relies on donations for maintenance and upgrades. As a club, we help
                    run events like tournaments and leagues, organize course work parties for maintenance and upkeep,
                    and manage the lost and found returns!</p>
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
