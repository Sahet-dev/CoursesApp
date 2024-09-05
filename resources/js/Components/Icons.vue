<template>
    <section class="why-us-section animated-element">
        <div class="container">
            <h2 class="section-title">Why CourseApp.com?</h2>
            <div class="features-grid">

                <!--                Challenges that help you learn faster-->
                <div class="feature-item">

                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20.707 20" class="icon-svg">
                        <defs>
                            <linearGradient id="gradient-fill" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color: #55c661; stop-opacity: 1" />
                                <stop offset="100%" style="stop-color: #0072ff; stop-opacity: 1" />
                            </linearGradient>
                        </defs>
                        <g fill="url(#gradient-fill)">
                            <rect x="8.707" y="1" width="12" height="2"/>
                            <rect x="1.707" width="4" height="4"/>
                            <rect x="8.707" y="9" width="12" height="2"/>
                            <rect x="8.707" y="17" width="12" height="2"/>
                            <rect x="1.707" y="16" width="4" height="4"/>
                            <polygon points="2.707 13.414 7.414 8.707 6 7.293 2.707 10.586 1.414 9.293 0 10.707 2.707 13.414"/>
                        </g>
                    </svg>
                    <h3 class="feature-title">Challenges that help you learn faster</h3>
                </div>

                <!--                Trusted by people like you-->
                <div   class="feature-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <defs>
                            <linearGradient id="gradient-fill" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#55C661FF; stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#0072FFFF; stop-opacity:1" />
                            </linearGradient>
                        </defs>
                        <g fill="url(#gradient-fill)">
                            <path d="M12,1.976,1.924,4.215,2,5.09c.107,1.175,1.139,11.54,4.441,13.742A52.689,52.689,0,0,0,11.553,21.9l.447.223.447-.223a52.833,52.833,0,0,0,5.108-3.063C20.857,16.63,21.889,6.265,22,5.09l.079-.875Zm4.445,15.192c-2.1,1.4-3.7,2.3-4.445,2.7-.741-.4-2.35-1.3-4.445-2.7C5.791,15.992,4.547,9.9,4.085,5.783L12,4.024l7.915,1.759C19.453,9.9,18.209,15.992,16.445,17.168Z"/>
                            <polygon points="9.707 10.293 8.293 11.707 12.155 15.57 16.832 8.555 15.168 7.445 11.845 12.43 9.707 10.293"/>
                        </g>
                    </svg>



                    <h3 class="feature-title">Trusted by people like you</h3>
                </div>

                <!--                Tutorials by experts-->
                <div   class="feature-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="21.87" height="20.801" viewBox="0 0 21.87 20.801">
                        <defs>
                            <linearGradient id="polygon-gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#55C661FF; stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#0072FFFF; stop-opacity:1" />
                            </linearGradient>
                        </defs>
                        <polygon points="4.178 20.801 10.936 15.891 17.692 20.801 15.112 12.855 21.87 7.945 13.518 7.945 10.936 0 8.354 7.945 0 7.945 6.758 12.855 4.178 20.801" fill="url(#polygon-gradient)"/>
                    </svg>



                    <h3 class="feature-title">Tutorials by experts</h3>
                </div>
                <!--                Guides downloads for each tutorial-->
                <div   class="feature-item">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <title>Artboard-154</title>
                        <defs>
                            <linearGradient id="gradient-fill" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#55C661FF; stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#0072FFFF; stop-opacity:1" />
                            </linearGradient>
                        </defs>
                        <g id="Download">
                            <path d="M11.292,16.706a1,1,0,0,0,1.416,0l3-3a1,1,0,0,0-1.414-1.414L13,13.586V4a1,1,0,0,0-2,0v9.586L9.707,12.293a1,1,0,0,0-1.414,1.414Z" fill="url(#gradient-fill)"/>
                            <path d="M17,19H7a1,1,0,0,0,0,2H17a1,1,0,0,0,0-2Z" fill="url(#gradient-fill)"/>
                        </g>
                    </svg>


                    <h3 class="feature-title">Guides downloads for each tutorial</h3>
                </div>
            </div>
        </div>
    </section>

</template>

<script setup>
import {ref} from "vue";

const features = ref([
    {
        id: 1,
        title: 'Challenges that help you learn faster',
        iconClass: 'icon-challenge',
        iconSvg:`
            <svg xmlns="http://www.w3.org/2000/svg" width="20.707" height="20" viewBox="0 0 20.707 20">
              <g>
                <rect x="8.707" y="1" width="12" height="2"/>
                <rect x="1.707" width="4" height="4"/>
                <rect x="8.707" y="9" width="12" height="2"/>
                <rect x="8.707" y="17" width="12" height="2"/>
                <rect x="1.707" y="16" width="4" height="4"/>
                <polygon points="2.707 13.414 7.414 8.707 6 7.293 2.707 10.586 1.414 9.293 0 10.707 2.707 13.414"/>
              </g>
            </svg>

        `
    },
    {
        id: 2,
        title: 'Trusted by people like you',
        iconClass: 'icon-trusted',
        iconSvg: `
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
              <g>
                <path d="M12,1.976,1.924,4.215,2,5.09c.107,1.175,1.139,11.54,4.441,13.742A52.689,52.689,0,0,0,11.553,21.9l.447.223.447-.223a52.833,52.833,0,0,0,5.108-3.063C20.857,16.63,21.889,6.265,22,5.09l.079-.875Zm4.445,15.192c-2.1,1.4-3.7,2.3-4.445,2.7-.741-.4-2.35-1.3-4.445-2.7C5.791,15.992,4.547,9.9,4.085,5.783L12,4.024l7.915,1.759C19.453,9.9,18.209,15.992,16.445,17.168Z"/>
                <polygon points="9.707 10.293 8.293 11.707 12.155 15.57 16.832 8.555 15.168 7.445 11.845 12.43 9.707 10.293"/>
              </g>
            </svg>
        `
    },
    {
        id: 3,
        title: 'Tutorials by experts',
        iconClass: 'icon-expert',
        iconSvg:`
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
              <g>
                <path d="M12,1.976,1.924,4.215,2,5.09c.107,1.175,1.139,11.54,4.441,13.742A52.689,52.689,0,0,0,11.553,21.9l.447.223.447-.223a52.833,52.833,0,0,0,5.108-3.063C20.857,16.63,21.889,6.265,22,5.09l.079-.875Zm4.445,15.192c-2.1,1.4-3.7,2.3-4.445,2.7-.741-.4-2.35-1.3-4.445-2.7C5.791,15.992,4.547,9.9,4.085,5.783L12,4.024l7.915,1.759C19.453,9.9,18.209,15.992,16.445,17.168Z"/>
                <polygon points="9.707 10.293 8.293 11.707 12.155 15.57 16.832 8.555 15.168 7.445 11.845 12.43 9.707 10.293"/>
              </g>
            </svg>
        `
    },
    {
        id: 4,
        title: 'Guides downloads for each tutorial',
        iconClass: 'icon-guides',
        iconSvg: `
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <g id="Download">
                    <path d="M11.292,16.706a1,1,0,0,0,1.416,0l3-3a1,1,0,0,0-1.414-1.414L13,13.586V4a1,1,0,0,0-2,0v9.586L9.707,12.293a1,1,0,0,0-1.414,1.414Z"
                    style="fill:#1c1b1e"/>
                    <path d="M17,19H7a1,1,0,0,0,0,2H17a1,1,0,0,0,0-2Z" style="fill:#1c1b1e"/>
                </g>
            </svg>`
    }
]);

</script>
<style>

/* Why Us Section */
.why-us-section {
    background: linear-gradient(135deg, #ebf8ff 0%, #ddd8e7 100%); /* Light blue gradient */
    padding: 120px 0; /* Adjusted padding */
}

/* Container */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 16px;
    text-align: center;
}

/* Section Title */
.section-title {
    font-size: 2.25rem; /* Font size for the title */
    font-weight: bold;
    margin-bottom: 32px;
    color: #2a4365; /* Dark blue color for the title */
}

/* Features Grid */
.features-grid {
    display: grid;
    grid-template-columns: 1fr; /* Single column by default */
    gap: 32px; /* Space between items */
}

@media (min-width: 768px) {
    .features-grid {
        grid-template-columns: 1fr 1fr; /* Two columns on medium screens */
    }
}

@media (min-width: 1024px) {
    .features-grid {
        grid-template-columns: 1fr 1fr 1fr 1fr; /* Four columns on large screens */
    }
}

/* Feature Item */
.feature-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    justify-content: center;
    padding: 20px;
    background-color: white; /* White background for each item */
    border-radius: 8px; /* Rounded corners */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
}

/* SVG Icons */
.feature-item svg {
    width: 48px;
    height: 48px;
    margin-bottom: 16px;
    color: #2a4365; /* Dark blue color for icons */
}

/* Feature Title */
.feature-title {
    font-size: 1.25rem; /* Font size for feature titles */
    font-weight: 600;
    color: #2d3748; /* Dark gray color for titles */
    margin-top: 12px;
}
.feature-icon-circle {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px; /* Adjust as needed */
    height: 48px; /* Adjust as needed */
    border-radius: 50%;
    background: linear-gradient(135deg, #00c6ff, #0072ff);
    padding: 4px; /* Optional: Add padding to create some space around the icon */
}

.feature-icon-circle svg {
    width: 24px;
    height: 24px;
    fill: #1c1b1e; /* Fill color for the SVG */
}

.background-circle {
    fill: none; /* Ensures the SVG paths are visible and the gradient is seen as background */
}
.icon-svg {
    width: 24px; /* Adjust the size of the SVG as needed */
    height: 24px;
    fill: #1c1b1e;
    flex-shrink: 0; /* Prevents the SVG from shrinking */
}

</style>
