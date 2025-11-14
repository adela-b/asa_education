document.addEventListener("DOMContentLoaded", () => {
  const goToCoursesBtn = document.getElementById("goToCoursesBtn");
  const coursesSection = document.getElementById("courses");

  // Smooth scroll
  window.scrollToSection = function (id) {
    const target = document.getElementById(id);
    if (target) target.scrollIntoView({ behavior: "smooth" });
  };

  if (goToCoursesBtn && coursesSection) {
    goToCoursesBtn.addEventListener("click", () => {
      coursesSection.scrollIntoView({ behavior: "smooth" });
    });
  }

  // Hamburger menu
  const toggle = document.getElementById("menu-toggle");
  const navLinks = document.getElementById("nav-links");

  if (toggle && navLinks) {
    toggle.addEventListener("click", () => {
      navLinks.classList.toggle("open");
      toggle.classList.toggle("open");
    });

    navLinks.querySelectorAll("a").forEach(link => {
      link.addEventListener("click", () => {
        navLinks.classList.remove("open");
        toggle.classList.remove("open");
      });
    });
  }
});

// Loader fade
window.addEventListener("load", () => {
  const loader = document.getElementById("loader");
  if (loader) setTimeout(() => loader.classList.add("hidden"), 1000);
});

/* ======================================
   LOGIN POPUP FUNCTIONS
====================================== */
function openLogin() {
  document.getElementById("loginModal").style.display = "flex";
}

function closeLogin() {
  document.getElementById("loginModal").style.display = "none";
}

// Close modal when clicking outside
window.onclick = function (event) {
  const modal = document.getElementById("loginModal");
  if (event.target === modal) {
    modal.style.display = "none";
  }
};

/* ===========================
   USER DROPDOWN TOGGLE
=========================== */
document.addEventListener("click", function (event) {
  const dropdown = document.querySelector(".user-dropdown");
  if (!dropdown) return;

  const menu = dropdown.querySelector(".dropdown-menu");
  const name = dropdown.querySelector(".user-name");

  if (name.contains(event.target)) {
    // Toggle menu
    menu.style.display = menu.style.display === "flex" ? "none" : "flex";
  } else {
    // Close when clicking outside
    menu.style.display = "none";
  }
});

/* ===========================
   REGISTER POPUP LOGIC
=========================== */
function openRegister() {
  document.getElementById("registerModal").style.display = "flex";
}

function closeRegister() {
  document.getElementById("registerModal").style.display = "none";
}

// ALLOW SWITCHING BETWEEN LOGIN <-> REGISTER
function switchToLogin() {
  closeRegister();
  openLogin();
}

function switchToRegister() {
  closeLogin();
  openRegister();
}

// CLOSE MODALS WHEN CLICKING OUTSIDE
window.addEventListener("click", function (event) {
  const loginModal = document.getElementById("loginModal");
  const registerModal = document.getElementById("registerModal");

  if (event.target === loginModal) loginModal.style.display = "none";
  if (event.target === registerModal) registerModal.style.display = "none";
});

/* ======================================
   COURSE SELECTION (NEW FEATURE)
====================================== */
let selectedCourse = null;

function selectCourse(course) {
    selectedCourse = course;

    document.querySelectorAll('.course-card').forEach(card => {
        card.classList.remove('selected');
    });

    event.currentTarget.classList.add('selected');
}


/* ======================================
   REGISTER BUTTON LOGIC (NEW FEATURE)
====================================== */
function registerForCourse() {
    if (!selectedCourse) {
        alert("Të lutem zgjidh një kurs fillimisht!");
        return;
    }

    const loggedIn = window.isLoggedIn;

    if (loggedIn) {
        // dergo direkt te dashboard dhe auto-shto kursin
        window.location.href = "dashboard.php?course=" + encodeURIComponent(selectedCourse);
    } else {
        // hap modal regjistrimi me kursin e zgjedhur
        document.querySelector("#registerModal input[name='selected_course']").value = selectedCourse;
        openRegister();
    }
}

