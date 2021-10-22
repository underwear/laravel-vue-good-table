<template>
    <div>
        <vue-good-table
                :maxHeight="maxHeight"
                :fixedHeader="fixedHeader"
                :theme="theme"
                :styleClass="styleClass"
                :lineNumbers="lineNumbers"
                :responsive="responsive"
                :rtl="rtl"
                :rowStyleClass="rowStyleClass"
                :groupOptions="groupOptions"
                :selectOptions="selectOptions"
                :sortOptions="sortOptions"
                :paginationOptions="paginationOptions"
                :searchOptions="searchOptions"

                mode="remote"
                :totalRows="totalRecords"
                :isLoading.sync="isLoading"
                :columns="columns"
                :rows="rows"
                @on-page-change="onPageChange"
                @on-sort-change="onSortChange"
                @on-column-filter="onColumnFilter"
                @on-per-page-change="onPerPageChange"
                @on-search="onSearch"
        >
            <wrapper>
                <b-table v-bind="$attrs" v-on="$listeners">
                    <template v-for="(_, slot) of $scopedSlots" v-slot:[slot]="scope">
                        <slot :name="slot" v-bind="scope"/>
                    </template>
                </b-table>
            </wrapper>
        </vue-good-table>
    </div>
</template>

<script>
    var qs = require('qs');

    export default {
        name: "laravel-vue-good-table",
        props: {
            dataUrl: {type: String},
            configUrl: {type: String},

            maxHeight: {default: null, type: String},
            fixedHeader: {default: false, type: Boolean},
            theme: {default: ''},
            styleClass: {default: 'vgt-table bordered'},

            lineNumbers: {default: false},
            responsive: {default: true},
            rtl: {default: false},
            rowStyleClass: {default: null, type: [Function, String]},

            groupOptions: {
                default() {
                    return {
                        enabled: false,
                        collapsable: false,
                    };
                },
            },

            selectOptions: {
                default() {
                    return {
                        enabled: false,
                        selectionInfoClass: '',
                        selectionText: 'rows selected',
                        clearSelectionText: 'clear',
                        disableSelectInfo: false,
                    };
                },
            },

            // sort
            sortOptions: {
                default() {
                    return {
                        enabled: true,
                        initialSortBy: {},
                        multipleColumns: true
                    };
                },
            },

            // pagination
            paginationOptions: {
                default() {
                    return {
                        enabled: true,
                        perPage: 10,
                        perPageDropdown: null,
                        position: 'bottom',
                        dropdownAllowAll: false,
                        mode: 'records', // or pages
                    };
                },
            },

            searchOptions: {
                default() {
                    return {
                        enabled: true,
                        trigger: 'enter',
                        placeholder: 'Search Table',
                    };
                },
            },
        },
        data() {
            return {
                columns: [],

                isLoading: false,

                serverParams: {
                    columnFilters: {},
                    sort: {
                        field: '',
                        type: ''
                    },
                    page: 1,
                    perPage: 10,
                    q: ''
                },

                totalRecords: 0,
                rows: [],
            };
        },
        methods: {
            updateParams(newProps) {
                this.serverParams = Object.assign({}, this.serverParams, newProps);
            },

            onPageChange(params) {
                this.updateParams({page: params.currentPage});
                this.fetchRows();
            },

            onPerPageChange(params) {
                this.updateParams({perPage: params.currentPerPage});
                this.fetchRows();
            },

            onSortChange(params) {
                this.updateParams({
                    sort: params,
                });
                this.fetchRows();
            },

            onColumnFilter(params) {
                this.updateParams(params);
                this.fetchRows();
            },

            onSearch(params) {

                this.serverParams.page = 1;
                this.updateParams({
                    q: params.searchTerm
                });
                this.fetchRows();
            },

            // rows loading

            fetchRows() {
                axios.request({
                    method: 'get',
                    url: this.dataUrl,
                    headers: {
                        'Accept': 'application/json'
                    },
                    params: this.serverParams,
                    paramsSerializer: params => {
                        return qs.stringify(params)
                    }
                })
                    .then(response => {
                        this.rows = response.data.rows;
                        this.totalRecords = response.data.totalRecords;
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },

            fetchColumns() {
                axios.request({
                    'method': 'get',
                    'url': this.configUrl
                }).then(response => {
                    this.columns = response.data.columns;
                }).catch(error => {
                    console.log(error);
                });
            }
        },
        created() {
            this.fetchRows();
            this.fetchColumns();
        }
    }
</script>
