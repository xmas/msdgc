<template>
    <div class="box-page">
        <div class="card-header">
            <span class="card-header-icon">
                <img src="/images/tag.svg" />
            </span>
            <h1 class="card-header-text">Club Membership</h1>
        </div>

        <p>&nbsp;</p>
        <div class="box-content">

            <h1 class="text-2xl font-bold text-center mb-4">
                Get a bag tag and join the club!
            </h1>
            <img src="images/north_parkers.jpg" class="w-full block object-cover" />

            <div class="club-name text-6xl font-bold text-zinc uppercase pb-4">
                        $35 / year
                    </div>
            <div class="flex flex-col md:flex-row items-center md:items-start gap-8">

                <div class="flex flex-col items-center md:items-start md:mr-8">

                    <img src="/images/tags.jpg" class="w-50" />
                </div>
                <div class="flex-1 flex flex-col gap-4">
                    <p>
                        Becoming a part of Mineral Springs Disc Golf Club is a three step process.
                        Click the link below and fill out the Membership Form
                    </p>
                    <ul class="flex flex-col gap-2">
                        <li>
                            Pay your $35 membership dues (cash,
                            <a href="http://www.paypal.me/mineralspringsdgclub" target="_blank" class="text-blue-600 underline">PayPal</a>
                            or Zelle)
                        </li>
                        <li>
                            Show the confirmation and pick up your bag tag!
                        </li>
                        <li>
                            Email us if you have any questions regarding this process.<br />
                            <a href="mailto:info@mineralspringsdgc.com" class="text-blue-600 underline">info@mineralspringsdgc.com</a>
                        </li>
                        <p>
                            Thanks for considering and being a part of the club. Your membership gives you exclusive access to tag events & invitational tournaments, gives you a vote on club decisions and keeps you informed on events and work parties that help make the course more fun!
                        </p>
                        <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition uppercase bangers text-4xl mt-8">
                            <a href="https://forms.gle/5b1d7c8f3e6c9b2y8" target="_blank" rel="noopener">Membership Form</a>
                        </button>
                    </ul>
                </div>
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
