/**
 * Landing Page JavaScript
 *
 * Handles smooth scrolling navigation for the landing page.
 * When a link with href starting with "#" is clicked, smoothly scrolls to the target section.
 *
 * This provides a better user experience for single-page navigation on the landing page.
 */
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll handler for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();

            // Get the target element
            const target = document.querySelector(this.getAttribute('href'));

            // Smooth scroll to target if it exists
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
