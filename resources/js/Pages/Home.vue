<script setup>
    import Nav from '../Shared/Nav.vue';
</script>
<template>
    <Nav />
    <main class="landing d-flex align-items-center">

        <div class="container text-center">

            <h1 class="fw-semibold mb-3">
                Build a better resume with AI
            </h1>

            <p class="lead mb-5 mx-auto">
                Analyse your CV, discover skill gaps, and generate a tailored
                professional resume in seconds.
            </p>

            <a href="/create" class="btn btn-outline-light btn-lg px-4 py-2">
                Create
            </a>

        </div>

    </main>
</template>

<style scoped>
.landing {
    min-height: 100vh;
    background: #242424;
    color: #f5f5f5;
}

/* Keep typography calm + elegant */
h1 {
    font-size: 2.6rem;
    letter-spacing: -0.5px;
}

.lead {
    max-width: 520px;
    color: #a1a1a1;
}

/* Softer button than default bootstrap */
.btn-outline-light {
    border-color: #3a3a3a;
    color: #f5f5f5;
    transition: all 0.25s ease;
}

.btn-outline-light:hover {
    background: #f5f5f5;
    color: #242424;
    border-color: #f5f5f5;
}
</style>
