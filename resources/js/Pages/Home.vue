<script setup>
    import Nav from '../Shared/Nav.vue';
    import { usePage } from '@inertiajs/vue3'

    const page = usePage()
</script>
<template>
    <Nav />
    <main class="landing d-flex align-items-center">
        <div class="container text-center">
            <div class="mb-4" v-if="$page.props.flash.message">
                <h3 class="text-warning">{{ $page.props.flash.message }}</h3>
            </div>
            <h1 class="fw-semibold mb-3">
                Build a better resume in seconds
            </h1>
            <p class="lead mb-5 mx-auto">
                Analyse your CV, discover skill gaps, and generate a tailored
                professional resume in seconds.
            </p>

            <a v-if="$page.props.isLoggedIn" href="/create" class="btn btn-outline-light btn-lg px-4 py-2">
                Create
            </a>
            <a v-else href="/login" class="btn btn-outline-light btn-lg px-4 py-2">
                Login
            </a>

            <div class="container py-5 text-white">
                <h2 class="text-center mb-5">How It Works</h2>

                <div class="work-wrapper row text-center g-4">
                    <div class="work-item col-md-4">
                        <h5>1. Upload Your CV</h5>
                        <p class="text-secondary">
                            Paste your resume and the job description.
                        </p>
                    </div>

                    <div class="work-item col-md-4">
                        <h5>2. Analyse & Improve</h5>
                        <p class="text-secondary">
                            We highlight missing skills and optimization opportunities.
                        </p>
                    </div>

                    <div class="work-item col-md-4">
                        <h5>3. Generate Tailored Resume</h5>
                        <p class="text-secondary">
                            Instantly get a job-specific professional resume.
                        </p>
                    </div>
                </div>
            </div>

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
.work-item{
    /* border: 1px solid #42b883; */
    padding: 2rem 0.5rem;
    border-radius: 10px;

    transition-duration: 300ms;
}
.work-item:hover{
    transform: scale(1.05)
}
/* .work-wrapper{
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    } */
</style>
