<script setup>
    import { Link } from '@inertiajs/vue3'
</script>

<template>
    <nav class="navbar navbar-expand-lg bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="/">ApplyWise</a>
            <div class="text-white" v-if="$page.props.isLoggedIn">
                {{ parseInt($page.props.credit) }} credits left
            </div>
            <button class="navbar-toggler bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active text-white" aria-current="page" href="/support">Support</a>
                    </li>
                    <li class="nav-item" v-if="$page.props.isLoggedIn">
                        <a class="nav-link text-white" href="/create">Create</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/pricing">Pricing</a>
                    </li>
                    <li class="nav-item" v-if="$page.props.isLoggedIn">
                        <Link href="logout" class="btn btn-danger" method="post" as="button">Logout</Link>
                    </li>
                    <li class="nav-item" v-if="!$page.props.isLoggedIn">
                        <a href="login" class="btn btn-success">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>
<style scoped>
   .navbar-expand-lg .navbar-collapse{
        justify-content: flex-end;
    }
</style>
