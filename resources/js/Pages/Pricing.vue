<script setup>
import { useForm } from '@inertiajs/vue3'
import Nav  from '../Shared/Nav.vue'
const props = defineProps({ packages: Array})

const form = useForm({
    price_id: null,
    credit: null
})

const handleSubmit = (item) => {
    form.price_id = item.price_id
    form.credit = item.credit
    form.post('/checkout')
}
</script>

<template>
    <Nav />
    <main class="py-5" style="background-color: #242424; min-height: 100vh; color: white;">
        <div class="container">
            <div class="text-center mb-5">
                <h1 class="fw-light">Credit Packages</h1>
                <p class="text-secondary">Choose the package that fits your needs</p>
            </div>

            <div class="row g-4 justify-content-center">
                <div
                    v-for="item in packages"
                    :key="item.price_id"
                    class="col-12 col-sm-6 col-lg-4"
                >
                    <div
                        class="card h-100 border-0 shadow-lg"
                        style="background-color: #2e2e2e; color: white; border-radius: 16px;"
                    >
                        <div class="card-body d-flex flex-column text-center p-4">
                            <h4 class="fw-light mb-3">{{ item.name }}</h4>

                            <div class="display-6 fw-bold mb-2">
                                {{ item.credit }}
                            </div>

                            <p class="text-secondary mb-4">
                                Credits
                            </p>
                            <p>{{ item.credit }} resumes</p>

                            <div class="fs-4 mb-4">
                                £{{ item.price }}
                            </div>

                            <button v-if="$page.props.isLoggedIn"
                                @click="handleSubmit(item)"
                                class="btn btn-light mt-auto"
                                style="border-radius: 10px;"
                            >
                                Buy Package
                            </button>
                            <a v-else href="/login" class="btn btn-success">Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>
