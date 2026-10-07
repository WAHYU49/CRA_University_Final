document.addEventListener("DOMContentLoaded", () => {

    // ========================================
    // ELEMENTS
    // ========================================

    const header = document.getElementById("header");
    const nav = document.getElementById("nav");
    const menuButton = document.getElementById("menuButton");
    const navLinks = document.querySelectorAll(".nav-link");
    const revealElements = document.querySelectorAll(".reveal");
    const counters = document.querySelectorAll(".commercial-number");
    const contactForm = document.getElementById("contactForm");


    // ========================================
    // MOBILE NAVBAR
    // ========================================

    if (menuButton && nav) {

        menuButton.addEventListener("click", () => {

            nav.classList.toggle("active");
            menuButton.classList.toggle("active");

            const isOpen = nav.classList.contains("active");

            document.body.classList.toggle("menu-open", isOpen);

            menuButton.setAttribute(
                "aria-expanded",
                isOpen ? "true" : "false"
            );

            menuButton.setAttribute(
                "aria-label",
                isOpen ? "Close menu" : "Open menu"
            );

        });


        navLinks.forEach((link) => {

            link.addEventListener("click", () => {

                nav.classList.remove("active");
                menuButton.classList.remove("active");
                document.body.classList.remove("menu-open");

                menuButton.setAttribute(
                    "aria-expanded",
                    "false"
                );

                menuButton.setAttribute(
                    "aria-label",
                    "Open menu"
                );

            });

        });

    }


    // ========================================
    // HERO CAROUSEL
    // ========================================

    const heroSlides =
        document.querySelectorAll(".hero-slide");

    const heroDots =
        document.querySelectorAll(".hero-dot");

    const previousSlide =
        document.querySelector(".hero-previous");

    const nextSlide =
        document.querySelector(".hero-next");


    if (heroSlides.length && heroDots.length) {

        let activeSlide = 0;

        function showSlide(index) {

            activeSlide =
                (index + heroSlides.length) %
                heroSlides.length;

            heroSlides.forEach((slide, slideIndex) => {

                const isActive =
                    slideIndex === activeSlide;

                slide.classList.toggle(
                    "active",
                    isActive
                );

                slide.classList.toggle(
                    "is-active",
                    isActive
                );

                slide.setAttribute(
                    "aria-hidden",
                    isActive ? "false" : "true"
                );

            });


            heroDots.forEach((dot, dotIndex) => {

                const isActive =
                    dotIndex === activeSlide;

                dot.classList.toggle(
                    "active",
                    isActive
                );

                if (isActive) {

                    dot.setAttribute(
                        "aria-current",
                        "true"
                    );

                } else {

                    dot.removeAttribute(
                        "aria-current"
                    );

                }

            });

        }


        heroDots.forEach((dot, index) => {

            dot.addEventListener(
                "click",
                () => showSlide(index)
            );

        });


        previousSlide?.addEventListener(
            "click",
            () => showSlide(activeSlide - 1)
        );


        nextSlide?.addEventListener(
            "click",
            () => showSlide(activeSlide + 1)
        );


        showSlide(0);

    }


    // ========================================
    // HERO TOUCH / AUTO PLAY
    // ========================================

    const carousel =
        document.querySelector("[data-hero-carousel]");


    if (carousel) {

        const slides =
            Array.from(
                carousel.querySelectorAll(".hero-slide")
            );

        const prefersReducedMotion =
            window.matchMedia(
                "(prefers-reduced-motion: reduce)"
            ).matches;

        let activeIndex = 0;
        let timer;
        let touchStartX = 0;


        const showHeroSlide = (index) => {

            if (!slides.length) {
                return;
            }

            activeIndex =
                (index + slides.length) %
                slides.length;


            slides.forEach((slide, i) => {

                slide.classList.toggle(
                    "is-active",
                    i === activeIndex
                );

                slide.setAttribute(
                    "aria-hidden",
                    String(i !== activeIndex)
                );

            });

        };


        const stopAutoPlay = () => {

            window.clearInterval(timer);

        };


        const startAutoPlay = () => {

            stopAutoPlay();

            if (
                !prefersReducedMotion &&
                slides.length > 1
            ) {

                timer = window.setInterval(() => {

                    showHeroSlide(
                        activeIndex + 1
                    );

                }, 8000);

            }

        };


        carousel.addEventListener(
            "touchstart",
            (event) => {

                touchStartX =
                    event.changedTouches[0].clientX;

            },
            {
                passive: true
            }
        );


        carousel.addEventListener(
            "touchend",
            (event) => {

                const distance =
                    event.changedTouches[0].clientX -
                    touchStartX;

                if (Math.abs(distance) < 45) {
                    return;
                }

                showHeroSlide(
                    activeIndex +
                    (distance < 0 ? 1 : -1)
                );

                startAutoPlay();

            },
            {
                passive: true
            }
        );


        showHeroSlide(0);

        startAutoPlay();

    }


    // ========================================
    // ABOUT PAGE CAROUSEL
    // ========================================

    const clientTrack =
        document.querySelector(".client-track");

    const clientSlides =
        document.querySelectorAll(".client-slide");

    const clientDots =
        document.querySelectorAll(".client-dot");

    const clientArrows =
        document.querySelectorAll(
            "[data-client-direction]"
        );


    if (
        clientTrack &&
        clientSlides.length &&
        clientDots.length
    ) {

        let activeClient = 0;


        function showClient(index) {

            activeClient =
                (index + clientSlides.length) %
                clientSlides.length;


            clientTrack.style.transform =
                `translateX(-${activeClient * 100}%)`;


            clientDots.forEach(
                (dot, dotIndex) => {

                    const isActive =
                        dotIndex === activeClient;

                    dot.classList.toggle(
                        "active",
                        isActive
                    );


                    if (isActive) {

                        dot.setAttribute(
                            "aria-current",
                            "true"
                        );

                    } else {

                        dot.removeAttribute(
                            "aria-current"
                        );

                    }

                }
            );

        }


        clientDots.forEach((dot, index) => {

            dot.addEventListener(
                "click",
                () => showClient(index)
            );

        });


        clientArrows.forEach((arrow) => {

            arrow.addEventListener(
                "click",
                () => {

                    const direction =
                        Number(
                            arrow.dataset.clientDirection
                        );

                    showClient(
                        activeClient + direction
                    );

                }
            );

        });

    }


    // ========================================
    // OFFER CAROUSEL
    // ========================================

    const offerSlides =
        document.querySelectorAll(".offer-slide");

    const offerDots =
        document.querySelectorAll(".offer-dot");

    const offerPrevious =
        document.querySelector(".offer-prev");

    const offerNext =
        document.querySelector(".offer-next");


    if (
        offerSlides.length &&
        offerDots.length
    ) {

        let activeOffer = 0;


        function showOffer(index) {

            activeOffer =
                (index + offerSlides.length) %
                offerSlides.length;


            offerSlides.forEach(
                (slide, slideIndex) => {

                    const isActive =
                        slideIndex === activeOffer;

                    slide.classList.toggle(
                        "active",
                        isActive
                    );

                    slide.setAttribute(
                        "aria-hidden",
                        isActive ? "false" : "true"
                    );

                }
            );


            offerDots.forEach(
                (dot, dotIndex) => {

                    const isActive =
                        dotIndex === activeOffer;

                    dot.classList.toggle(
                        "active",
                        isActive
                    );


                    if (isActive) {

                        dot.setAttribute(
                            "aria-current",
                            "true"
                        );

                    } else {

                        dot.removeAttribute(
                            "aria-current"
                        );

                    }

                }
            );

        }


        offerDots.forEach((dot, index) => {

            dot.addEventListener(
                "click",
                () => showOffer(index)
            );

        });


        offerPrevious?.addEventListener(
            "click",
            () => showOffer(activeOffer - 1)
        );


        offerNext?.addEventListener(
            "click",
            () => showOffer(activeOffer + 1)
        );

    }


    // ========================================
    // HEADER SCROLL EFFECT
    // ========================================

    function handleHeaderScroll() {

        if (!header) {
            return;
        }


        if (window.scrollY > 50) {

            header.classList.add("scrolled");

        } else {

            header.classList.remove("scrolled");

        }

    }


    window.addEventListener(
        "scroll",
        handleHeaderScroll
    );

    handleHeaderScroll();


    // ========================================
    // ACTIVE NAVIGATION
    // ========================================

    function updateActiveNavigation() {

        const currentPath =
            window.location.pathname;


        navLinks.forEach((link) => {

            const linkHref =
                link.getAttribute("href");


            if (!linkHref) {
                return;
            }


            // Link menuju halaman lain
            if (!linkHref.startsWith("#")) {

                const linkPath =
                    new URL(
                        link.href,
                        window.location.origin
                    ).pathname;


                link.classList.toggle(
                    "active",
                    currentPath === linkPath
                );


                return;

            }

        });

    }


    updateActiveNavigation();


    // ========================================
    // REVEAL ANIMATION
    // ========================================

    if (revealElements.length > 0) {

        const revealObserver =
            new IntersectionObserver(
                (entries, observer) => {

                    entries.forEach((entry) => {

                        if (entry.isIntersecting) {

                            entry.target.classList.add(
                                "show"
                            );

                            observer.unobserve(
                                entry.target
                            );

                        }

                    });

                },
                {
                    threshold: 0.15
                }
            );


        revealElements.forEach((element) => {

            revealObserver.observe(element);

        });

    }


    // ========================================
    // NUMBER COUNTER
    // ========================================

    function animateCounter(element) {

        const target =
            Number(
                element.getAttribute(
                    "data-target"
                )
            );


        if (isNaN(target)) {
            return;
        }


        let current = 0;

        const duration = 1800;

        const startTime =
            performance.now();


        function updateCounter(currentTime) {

            const elapsed =
                currentTime - startTime;


            const progress =
                Math.min(
                    elapsed / duration,
                    1
                );


            const easeOut =
                1 -
                Math.pow(
                    1 - progress,
                    3
                );


            current =
                Math.floor(
                    target * easeOut
                );


            element.textContent =
                current.toLocaleString(
                    "en-US"
                );


            if (progress < 1) {

                requestAnimationFrame(
                    updateCounter
                );

            } else {

                element.textContent =
                    target.toLocaleString(
                        "en-US"
                    );

            }

        }


        requestAnimationFrame(
            updateCounter
        );

    }


    if (counters.length > 0) {

        const counterObserver =
            new IntersectionObserver(
                (entries, observer) => {

                    entries.forEach((entry) => {

                        if (entry.isIntersecting) {

                            animateCounter(
                                entry.target
                            );

                            observer.unobserve(
                                entry.target
                            );

                        }

                    });

                },
                {
                    threshold: 0.5
                }
            );


        counters.forEach((counter) => {

            counterObserver.observe(counter);

        });

    }


    // ========================================
    // CONTACT FORM
    // ========================================

    if (contactForm) {

        contactForm.addEventListener(
            "submit",
            (event) => {

                event.preventDefault();


                const formData =
                    new FormData(contactForm);


                const name =
                    String(
                        formData.get("name") || ""
                    ).trim();


                const email =
                    String(
                        formData.get("email") || ""
                    ).trim();


                const phone =
                    String(
                        formData.get("phone") || ""
                    ).trim();


                const message =
                    String(
                        formData.get("message") || ""
                    ).trim();


                const whatsappMessage = [

                    "Halo CRA University, saya ingin bertanya tentang program.",

                    `Nama: ${name}`,

                    `Email: ${email}`,

                    phone
                        ? `Nomor telepon: ${phone}`
                        : "",

                    `Pesan: ${message}`

                ]
                .filter(Boolean)
                .join("\n");


                window.open(
                    `https://wa.me/6285755976016?text=${encodeURIComponent(
                        whatsappMessage
                    )}`,
                    "_blank",
                    "noopener,noreferrer"
                );


                contactForm.reset();

            }
        );

    }


    // ========================================
    // BUTTON HOVER EFFECT
    // ========================================

    const buttons =
        document.querySelectorAll(".btn");


    buttons.forEach((button) => {

        button.addEventListener(
            "mouseenter",
            () => {

                button.style.transform =
                    "translateY(-2px)";

            }
        );


        button.addEventListener(
            "mouseleave",
            () => {

                button.style.transform = "";

            }
        );

    });


    // ========================================
    // ESCAPE KEY
    // ========================================

    document.addEventListener(
        "keydown",
        (event) => {

            if (event.key !== "Escape") {
                return;
            }


            if (nav) {

                nav.classList.remove(
                    "active"
                );

            }


            document.body.classList.remove(
                "menu-open"
            );


            if (menuButton) {

                menuButton.classList.remove(
                    "active"
                );


                menuButton.setAttribute(
                    "aria-expanded",
                    "false"
                );


                menuButton.setAttribute(
                    "aria-label",
                    "Open menu"
                );

            }

        }
    );


    // ========================================
    // DOKUMENTASI DARI DATABASE
    // ========================================

    async function loadDokumentasi() {

        const documentationGrid =
            document.getElementById(
                "documentationGrid"
            );


        // Tidak ada section dokumentasi
        if (!documentationGrid) {
            return;
        }


        try {

            const response =
                await fetch(
                    "api/dokumentasi.php"
                );


            if (!response.ok) {

                throw new Error(
                    "Gagal mengakses API dokumentasi"
                );

            }


            const result =
                await response.json();


            if (!result.success) {

                throw new Error(
                    "Data dokumentasi gagal diambil"
                );

            }


            // Kosongkan isi lama
            documentationGrid.innerHTML = "";


            // Belum ada data
            if (
                !result.data ||
                result.data.length === 0
            ) {

                documentationGrid.innerHTML = `
                    <p>
                        Belum ada dokumentasi.
                    </p>
                `;

                return;

            }


            // Maksimal 6 gambar
            const dokumentasi =
                result.data.slice(0, 6);


            dokumentasi.forEach(
                (item, index) => {

                    const article =
                        document.createElement(
                            "article"
                        );


                    article.className =
                        "documentation-item";


                    if (index === 0) {

                        article.classList.add(
                            "large"
                        );

                    }


                    article.innerHTML = `

                        <div class="documentation-image">

                            <div class="image-placeholder media-placeholder">

                                <img
                                    src="assets/uploads/dokumentasi/${item.gambar}"
                                    alt="${escapeHtml(item.judul)}"
                                    loading="lazy"
                                >

                            </div>

                            <div class="documentation-overlay">

                                <span>
                                    ${escapeHtml(item.judul)}
                                </span>

                            </div>

                        </div>

                    `;


                    documentationGrid.appendChild(
                        article
                    );

                }
            );


        } catch (error) {

            console.error(
                "Error dokumentasi:",
                error
            );


            documentationGrid.innerHTML = `
                <p>
                    Dokumentasi belum dapat dimuat.
                </p>
            `;

        }

    }


    // ========================================
    // ESCAPE HTML
    // ========================================

    function escapeHtml(text) {

        const div =
            document.createElement("div");

        div.textContent =
            text ?? "";

        return div.innerHTML;

    }


    // Jalankan dokumentasi
    loadDokumentasi();

});