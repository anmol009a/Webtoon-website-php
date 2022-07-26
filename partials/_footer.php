<!-- Theme Button -->
<button type="button" title="Change Theme" class="btn btn-outline-light btn-sm" id="theme-button" onclick="toogleTheme();"><i class="fa fa-sun-o" aria-hidden="true"></i></button>

<!-- Go-to-top -->
<button onclick="topFunction()" id="myBtn" class="btn btn-outline-light" title="Go to top"><i class="fa fa-arrow-up" aria-hidden="true"></i></button>

<footer class="d-flex flex-wrap justify-content-between align-items-center p-1 border-top bg-dark">
    <p class="col-md-4 mb-0 text-muted">&copy; 2020-<?php echo date("Y");?> WebtoonWorld, Inc</p>
    
    <a href="/" class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
        <svg class="bi me-2" width="40" height="32">
            <use xlink:href="#bootstrap"></use>
        </svg>
    </a>

    <ul class="nav col-md-4 justify-content-end">
        <li class="nav-item"><a href="index.php" class="nav-link px-2 text-muted">Home</a></li>
        <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">FAQs</a></li>
        <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">Disclaimer</a></li>
        <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">About</a></li>
    </ul>
</footer>


<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

<script>
    //Get the button:
    mybutton = document.getElementById("myBtn");

    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function() {
        scrollFunction()
    };

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            mybutton.style.display = "block";
        } else {
            mybutton.style.display = "none";
        }
    }

    // When the user clicks on the button, scroll to the top of the document
    function topFunction() {
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
    }
</script>

<script>
    // ==============================================
    // toogle method to change theme
    function toogleTheme() {
        // body
        let element = document.body;
        element.classList.toggle("bg-dark");
        element.classList.toggle("text-light");

        // top to btn
        element = document.getElementById("myBtn");
        element.classList.toggle("btn-outline-light");
        element.classList.toggle("btn-outline-dark");

        // theme btn
        element = document.getElementById("theme-button");
        element.classList.toggle("btn-outline-light");
        element.classList.toggle("btn-success");
    }
</script>