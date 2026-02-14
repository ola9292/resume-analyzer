<script setup>
    import Nav from '../Shared/Nav.vue';
    import { useForm } from '@inertiajs/vue3'

    const form = useForm({
        name: null,
        email: null,
        message: null,
    })
</script>
<template>
    <Nav />
    <main class="" style="background-color: #242424; min-height: 100vh; color: white;">
        <div class="pt-4 container">
            <h1>Support</h1>
            <p>Send us a message if you have any difficulties using the system.</p>
            <div class="alert alert-success mb-4" v-if="$page.props.flash.message">
                {{ $page.props.flash.message }}
            </div>
            <form @submit.prevent="form.post('/support')">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" placeholder="John Doe" v-model="form.name">
                    <div class="text-danger" v-if="form.errors.name">{{ form.errors.name }}</div>
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Email address</label>
                    <input type="text" class="form-control" placeholder="johndoe@example.com" v-model="form.email">
                    <div class="text-danger" v-if="form.errors.email">{{ form.errors.email }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Message</label>
                    <textarea class="form-control" rows="5" v-model="form.message"></textarea>
                    <div class="text-danger" v-if="form.errors.message">{{ form.errors.message }}</div>
                </div>
                <button class="btn btn-success" type="submit">Send Message</button>
            </form>
        </div>

    </main>
</template>
