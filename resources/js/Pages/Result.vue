<script setup>
const emit = defineEmits(['toggle-edit'])
const props = defineProps({
    analysis: Object,
    isEditing: Boolean
})


</script>

<template>
        <!-- Score -->
        <div class="text-center mb-5">
            <h2 class="fw-semibold mb-2">Resume Analysis</h2>

            <div class="display-4 fw-bold text-primary">
                {{ analysis.match_score }}%
            </div>

            <p class="text-muted">Match score</p>
        </div>

        <div class="row g-4">
            <!-- Strengths -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="fw-semibold mb-3">Strengths</h5>

                        <ul class="list-group list-group-flush">
                            <li
                                v-for="(item,i) in analysis.strengths"
                                :key="i"
                                class="list-group-item px-0"
                            >
                                {{ item }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Missing Skills -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="fw-semibold mb-3">Missing Skills</h5>

                        <ul v-if="analysis.missing_skills.length > 0" class="list-group list-group-flush">
                            <li
                                v-for="(item,i) in analysis.missing_skills"
                                :key="i"
                                class="list-group-item px-0 text-muted"
                            >
                                {{ item }}
                            </li>
                        </ul>
                        <div v-else>
                            <p>Strong fit, No missing skill.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recommendations -->
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-semibold mb-3">Recommendations</h5>

                        <ul class="list-group list-group-flush">
                            <li
                                v-for="(item,i) in analysis.recommendations"
                                :key="i"
                                class="list-group-item px-0"
                            >
                                {{ item }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Generated Resume -->
            <button @click.prevent="emit('toggle-edit')" class="btn btn-warning">{{ isEditing ? 'Switch to print mode' : 'Switch to edit mode'}}</button>
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <!-- Header -->
                        <div class="mb-4 border-bottom pb-3">
                            <div>
                                <div v-if="isEditing">
                                    <input type="text" v-model="analysis.generated_resume.name">
                                </div>
                                <div v-else>
                                    {{ analysis.generated_resume.name }}
                                </div>
                            </div>


                            <div class="text-muted">
                                <div v-if="isEditing">
                                    <input type="text" v-model="analysis.generated_resume.title">
                                </div>
                                <div v-else>
                                    {{ analysis.generated_resume.title }}
                                </div>
                            </div>

                            <div class="small text-muted mt-2">
                                <!-- EDIT MODE -->
                                <div v-if="isEditing" class="d-flex flex-wrap gap-2">
                                    <input
                                        class="form-control form-control-sm"
                                        placeholder="Email"
                                        v-model="analysis.generated_resume.email"
                                    />

                                    <input
                                        class="form-control form-control-sm"
                                        placeholder="Phone"
                                        v-model="analysis.generated_resume.phone"
                                    />

                                    <input
                                        class="form-control form-control-sm"
                                        placeholder="Location"
                                        v-model="analysis.generated_resume.location"
                                    />
                                </div>

                                <!-- DISPLAY MODE -->
                                <div v-else>
                                    <span v-if="analysis.generated_resume.email">
                                        {{ analysis.generated_resume.email }}
                                    </span>

                                    <span v-if="analysis.generated_resume.phone">
                                        <span v-if="analysis.generated_resume.email"> • </span>
                                        {{ analysis.generated_resume.phone }}
                                    </span>

                                    <span v-if="analysis.generated_resume.location">
                                        <span v-if="analysis.generated_resume.email || analysis.generated_resume.phone"> • </span>
                                        {{ analysis.generated_resume.location }}
                                    </span>
                                </div>

                                </div>
                        </div>

                        <!-- Summary -->
                        <div class="mb-4">
                            <h6 class="fw-semibold">Professional Summary</h6>
                            <div v-if="isEditing">
                                <textarea
                                        class="form-control form-control-sm"
                                        placeholder="Summary"
                                        v-model="analysis.generated_resume.summary"
                                    />
                            </div>
                            <p v-else class="text-muted mb-0">
                                {{ analysis.generated_resume.summary }}
                            </p>
                        </div>

                        <!-- Skills -->
                        <div class="mb-4">
                            <h6 class="fw-semibold">Skills</h6>

                            <!-- EDIT MODE -->
                            <div v-if="isEditing" class="d-flex flex-wrap gap-2">

                                <div
                                    v-for="(skill, i) in analysis.generated_resume.skills"
                                    :key="i"
                                    class="d-flex align-items-center gap-1"
                                >
                                    <input
                                        class="form-control form-control-sm"
                                        style="width:auto; min-width:120px;"
                                        v-model="analysis.generated_resume.skills[i]"
                                    />

                                    <button
                                        class="btn btn-sm btn-outline-danger"
                                        @click="analysis.generated_resume.skills.splice(i,1)"
                                    >
                                        ×
                                    </button>
                                </div>

                                <!-- Add new skill -->
                                <button
                                    class="btn btn-sm btn-outline-dark"
                                    @click="analysis.generated_resume.skills.push('')"
                                >
                                    + Add
                                </button>

                            </div>

                            <!-- DISPLAY MODE -->
                            <div v-else class="d-flex flex-wrap gap-2">
                                <span
                                    v-for="(skill,i) in analysis.generated_resume.skills"
                                    :key="i"
                                    class="badge bg-light text-dark border"
                                >
                                    {{ skill }}
                                </span>
                            </div>

                        </div>


                        <!-- Experience -->
                        <div class="mb-4">
                            <h6 class="fw-semibold">Experience</h6>

                            <div
                                v-for="(exp,i) in analysis.generated_resume.experience"
                                :key="i"
                                class="mb-3"
                            >

                                <!-- Job Title -->
                                <div class="fw-semibold">
                                    <input
                                        v-if="isEditing"
                                        class="form-control form-control-sm"
                                        v-model="exp.job_title"
                                    />
                                    <span v-else>
                                        {{ exp.job_title }}
                                    </span>
                                </div>

                                <!-- Company + Location -->
                                <div class="text-muted small mt-1">
                                    <template v-if="isEditing">
                                        <input
                                            class="form-control form-control-sm mb-1"
                                            placeholder="Company"
                                            v-model="exp.company"
                                        />
                                        <input
                                            class="form-control form-control-sm"
                                            placeholder="Location"
                                            v-model="exp.location"
                                        />
                                    </template>

                                    <template v-else>
                                        {{ exp.company }} • {{ exp.location }}
                                    </template>
                                </div>

                                <!-- Dates -->
                                <div class="small text-muted mt-1">
                                    <input
                                        v-if="isEditing"
                                        class="form-control form-control-sm"
                                        v-model="exp.dates"
                                    />
                                    <span v-else>
                                        {{ exp.dates }}
                                    </span>
                                </div>

                                <!-- Responsibilities -->
                                <ul class="mt-2 mb-0">

                                    <li
                                        v-for="(r,idx) in exp.responsibilities"
                                        :key="idx"
                                    >
                                        <textarea
                                            v-if="isEditing"
                                            class="form-control form-control-sm mb-1"
                                            rows="2"
                                            v-model="exp.responsibilities[idx]"
                                        ></textarea>

                                        <span v-else>
                                            {{ r }}
                                        </span>
                                    </li>
                                </ul>

                            </div>
                        </div>

                        <!-- Education -->
                        <div v-if="analysis.generated_resume.education?.length">
                            <h6 class="fw-semibold">Education</h6>

                            <div
                                v-for="(edu,i) in analysis.generated_resume.education"
                                :key="i"
                                class="mb-2"
                            >

                                <!-- Qualification -->
                                <div class="fw-semibold">
                                    <input
                                        v-if="isEditing"
                                        class="form-control form-control-sm"
                                        v-model="edu.qualification"
                                        placeholder="Qualification"
                                    />
                                    <span v-else>
                                        {{ edu.qualification }}
                                    </span>
                                </div>

                                <!-- Institution + Dates -->
                                <div class="small text-muted mt-1">

                                    <template v-if="isEditing">
                                        <input
                                            class="form-control form-control-sm mb-1"
                                            placeholder="Institution"
                                            v-model="edu.institution"
                                        />

                                        <input
                                            class="form-control form-control-sm"
                                            placeholder="Dates"
                                            v-model="edu.dates"
                                        />
                                    </template>

                                    <template v-else>
                                        {{ edu.institution }} • {{ edu.dates }}
                                    </template>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
</template>

