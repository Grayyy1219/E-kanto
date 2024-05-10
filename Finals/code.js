// Get the slideshow container and slides
const slideshowContainer = document.querySelector('.slideshow-container');
const slidesContainer = document.querySelector('.slides-container');
const slides = document.querySelectorAll('.slide');
const totalSlides = slides.length;

// Set the current slide index to 0
let currentSlideIndex = 0;
let timer;

// Function to create navigation dots
function createDots() {
    const dotContainer = document.querySelector('.dot-container');
    for (let i = 0; i < totalSlides; i++) {
        const dot = document.createElement('span');
        dot.className = 'dot';
        dotContainer.appendChild(dot);
        dot.addEventListener('click', () => {
            currentSlideIndex = i;
            showSlide(currentSlideIndex);
            resetTimer();
        });
    }
    updateDots();
}

// Function to update the active dot
function updateDots() {
    const dots = document.querySelectorAll('.dot');
    dots.forEach((dot, index) => {
        if (index === currentSlideIndex) {
            dot.classList.add('active-dot');
        } else {
            dot.classList.remove('active-dot');
        }
    });
}

// Start the slideshow
showSlide(currentSlideIndex);

// Function to show the next slide
function nextSlide() {
    currentSlideIndex = (currentSlideIndex + 1) % totalSlides;
    showSlide(currentSlideIndex);
    updateDots();
}
// Function to show the previous slide
function prevSlide() {
    currentSlideIndex = (currentSlideIndex - 1 + totalSlides) % totalSlides;
    showSlide(currentSlideIndex);
}
// Start a timer to change the slide every 3.5 seconds
function startTimer() {
    timer = setInterval(nextSlide, 3500);
}

// Stop and reset the timer
function resetTimer() {
    clearInterval(timer);
    startTimer();
}

// Show the specified slide with a sliding effect
function showSlide(slideIndex) {
    slidesContainer.style.transform = `translateX(-${slideIndex * 100}%)`;

    // Remove the 'active' class from all slides
    slides.forEach(slide => slide.classList.remove('active'));

    // Add the 'active' class to the current slide
    slides[slideIndex].classList.add('active');
    updateDots();
}

// Add event listeners for mouseover and mouseout to stop and restart the timer
slideshowContainer.addEventListener('mouseover', () => {
    clearInterval(timer);
});

slideshowContainer.addEventListener('mouseout', () => {
    resetTimer();
});

const prevButton = document.querySelector('.prev-button');
const nextButton = document.querySelector('.next-button');

prevButton.addEventListener('click', () => {
    prevSlide();
    resetTimer();
});

nextButton.addEventListener('click', () => {
    nextSlide();
    resetTimer();
});

// Call createDots function to create the initial navigation dots
createDots();

// Start the timer initially
startTimer();
