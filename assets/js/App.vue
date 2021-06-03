<template>
    <div class="page-container">
        <v-app>
            <v-app-bar :clipped-left="$vuetify.breakpoint.lgAndUp" app color="primary" dark>
                <v-toolbar-title class="ml-0">
                    {{ appName }} {{ appVersion }}
                </v-toolbar-title>
            </v-app-bar>

            <v-main>
                <v-container>
                    <v-flex class="xs12 text-xs-center">
                        <v-form
                            ref="form"
                            v-model="formValid"
                            autocomplete="off"
                            lazy-validation
                            @keyup.native.enter="submit"
                        >
                            <v-select v-model="formData.searchEngine" :items="searchEngines" :rules="rules.searchEngine"
                                      item-text="name" item-value="value" label="Search Engine"/>

                            <v-text-field
                                v-model="formData.searchTerm"
                                :rules="rules.searchTerm"
                                autocapitalize="none"
                                autocorrect="off"
                                label="Introduce a search term"
                                required
                            ></v-text-field>

                            <v-btn :disabled="!formValid || formLoading" :loading="formLoading" block color="primary"
                                   large @click="submit">Search
                            </v-btn>
                        </v-form>
                    </v-flex>

                    <v-flex v-if="domains.items.length" class="xs12 mt-6">
                        <v-data-table
                            :headers="domains.headers"
                            :items="domains.items"
                            :items-per-page="10"
                            class="elevation-1"
                        ></v-data-table>
                    </v-flex>
                </v-container>
            </v-main>
            <v-footer app color="primary">
                <v-row>
                    <v-col class="mx-auto" cols="auto">
                        <span class="white--text body-2">&copy; {{ (new Date()).getFullYear() }} SearchEngineCrawler. All rights reserved.</span>
                    </v-col>
                </v-row>
            </v-footer>

            <v-snackbar v-model="snackbar.show" :color="snackbar.color">
                {{ snackbar.message }}

                <template v-slot:action="{ attrs }">
                    <v-btn color="white" text @click="snackbar.show = false">Close</v-btn>
                </template>
            </v-snackbar>
        </v-app>
    </div>
</template>

<script>
export default {
    name: "App",
    data: () => ({
        appName: "SearchEngineCrawler",
        appVersion: "v1.0",
        searchEngines: [
            //{name: 'Google', value: 'google'},
            {name: 'Bing', value: 'bing'},
            {name: 'DuckDuckGo', value: 'duckduckgo'},
        ],
        domains: {
            headers: [{text: 'Domain', value: 'domain'}, {text: 'Count', value: 'count'},],
            items: [],
        },
        formValid: true,
        formLoading: false,
        formData: {
            searchEngine: "bing",
            searchTerm: ""
        },
        rules: {
            searchTerm: [
                v => !!v || 'Please, introduce a search term',
                v => v.length <= 255 || 'Search term cannot be longer than 255 characters',
            ],
            searchEngine: [
                v => !!v || "Please, select a search engine",
            ]
        },
        snackbar: {
            show: false,
            color: 'info',
            message: ''
        }
    }),
    methods: {
        submit() {
            if (!this.$refs.form.validate()) return;
            this.formLoading = true;

            const apiPath = `/${this.formData.searchEngine}`;
            const params = {"s": this.formData.searchTerm};
            this.$http.get(apiPath, {params: params})
                .then((response) => {
                    this.domains.items = response.data;
                })
                .catch(error => {
                    if (error.response.status === 500) {
                        this.snackbar.message = "Unexpected error occurred. Please, try again later.";
                        this.snackbar.color = "error";
                        this.snackbar.show = true;
                    }
                })
                .then(() => {
                    this.formLoading = false;
                });
        },
    },
    computed: {},
    components: {},
}
</script>

<style lang="scss">
.v-toolbar__title {
    cursor: pointer;
}
</style>