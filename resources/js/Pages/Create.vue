<script setup>
import { ref } from 'vue'
import Nav  from '../Shared/Nav.vue'
import axios from 'axios'
import Result from './Result.vue';

const form = ref({
    description: null,
    resume: null,
    progress: null
})

const analysis = ref(null)
const loading = ref(false)
const isEditing = ref(false)
const errorMessage = ref(null)

const handleSubmit = async () => {

    if(!form.value.description || !form.value.resume){
        alert('enter description and upload resume');
        return
    }

    const data = new FormData()

    data.append('description', form.value.description)
    data.append('resume', form.value.resume)
    loading.value = true

    try {
        const response = await axios.post('/resume', data, {
            headers: {
                'Content-Type': 'multipart/form-data'
            },
            onUploadProgress: (event) => {
                form.value.progress = {
                    percentage: Math.round((event.loaded * 100) / event.total)
                }
            }
        })

        console.log(response)

        analysis.value = response.data.analysis

    } catch (error) {
        console.error(error)
        console.log(error.response)
        errorMessage.value = error.response.data.message
    }finally{
        loading.value = false
    }
}

const downloadResume = async () => {
    try {
        loading.value = true

        const response = await axios.post(
            '/resume/download',
            {
                resume: analysis.value.generated_resume
            },
            {
                responseType: 'blob' // IMPORTANT for PDF
            }
        )

        // Create file link
        const url = window.URL.createObjectURL(new Blob([response.data]))
        const link = document.createElement('a')
        link.href = url
        link.setAttribute('download', 'resume.pdf')
        document.body.appendChild(link)
        link.click()

        link.remove()

    } catch (error) {
        console.error(error)
    } finally {
        loading.value = false
    }
}

const handleToggleEdit = () => {
    isEditing.value = !isEditing.value
}

</script>


<template>
    <Nav />
    <main class="container mt-4">
        <header class="mb-5 text-center">
            <h1 class="fw-semibold">Analyse your resume</h1>
            <p class="text-muted mb-0">
                Discover skill gaps and generate a tailored CV in seconds.
            </p>
        </header>
        <div v-if="errorMessage" class="alert alert-warning" role="alert">
            {{ errorMessage }}
        </div>
        <div v-if="analysis" class="container py-5">
            <Result :analysis="analysis" @toggle-edit="handleToggleEdit" :isEditing="isEditing"/>
            <button
                class="btn btn-dark mt-4"
                @click="downloadResume"
            >
                {{ loading ? 'Generating PDF...' : 'Download Resume PDF' }}
            </button>
        </div>

        <div v-else>
            <div v-if="loading">
                <div class="spinner-grow" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p>Analysing...</p>
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Upload Resume</label>
                <input type="file" class="form-control" @input="form.resume = $event.target.files[0]">
                <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                    {{ form.progress.percentage }}%
                </progress>
            </div>
            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Target Job Description</label>
                <textarea class="form-control" v-model="form.description" rows="8"></textarea>
            </div>
            <button @click.prevent="handleSubmit" class="btn btn-warning">{{loading ? 'Analysing...' : 'Submit'}}</button>
        </div>

    </main>

</template>
