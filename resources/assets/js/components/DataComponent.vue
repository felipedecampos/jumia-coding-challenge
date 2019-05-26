<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="ccol-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-left">
                                <h2><strong>Phone numbers</strong></h2>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        <select
                                            name="country"
                                            id="country"
                                            v-model="country"
                                            class="form-control form-control-xs"
                                            v-on:change="getResults"
                                        >
                                            <option value="null">Select country</option>
                                            <option v-for="(item, index) in countriesList" :key="index" :value="index">
                                                {{ item }}
                                            </option>
                                        </select>
                                    </th>
                                    <th>
                                        <select
                                            name="state"
                                            id="state"
                                            v-model="state"
                                            class="form-control form-control-xs"
                                            v-on:change="getResults"
                                        >
                                            <option value="null">Valid phone numbers</option>
                                            <option value="OK">OK</option>
                                            <option value="NOK">NOK</option>
                                        </select>
                                    </th>
                                    <th colspan="2">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="bg-info"><strong>Country</strong></td>
                                    <td class="bg-info"><strong>State</strong></td>
                                    <td class="bg-info"><strong>Country code</strong></td>
                                    <td class="bg-info"><strong>Phone num.</strong></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr v-for="tag in laravelData.data">
                                    <td>
                                        {{ tag.country }}
                                    </td>
                                    <td>
                                        <span :class="
                                            [tag.state === 'OK' ? 'badge badge-success' : 'badge badge-danger']
                                        ">
                                            {{ tag.state }}
                                        </span>
                                    </td>
                                    <td>
                                        +{{ tag.code }}
                                    </td>
                                    <td>
                                        {{ tag.phone_number }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            <pagination :data="laravelData" @pagination-change-page="getResults"></pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.');
        },
        data() {
            return {
                laravelData: {},
                countriesList: {},
                country: null,
                state: null,
            }
        },
        created() {
            console.log('Component created.');
            this.getResults();
            this.listCountries();
        },
        watch: {
            country (after, before) {
                this.getResults();
            },
            state (after, before) {
                this.getResults();
            }
        },
        methods: {
            getResults(page) {
                if (typeof page === 'undefined') {
                    page = 1;
                }

                let qryString = '';

                if (typeof this.country === 'string' && this.country !== 'null' && this.country !== '') {
                    qryString += '&country=' + this.country;
                }

                if (typeof this.state === 'string' && this.state !== 'null' && this.state !== '') {
                    qryString += '&state=' + this.state;
                }

                this.$http.get('/customers?page=' + page + qryString)
                    .then(response => {
                    return response.json();
                }).then(data => {
                    this.laravelData = data;
                });
            },
            listCountries() {
                this.$http.get('/countries')
                .then(response => {
                    return response.json();
                }).then(data => {
                    this.countriesList = data;
                });
            }
        }
    }
</script>