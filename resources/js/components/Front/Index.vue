<template>
    <div class="container" :class="{ loading: loading }">
        <div v-if="!loading">
            <form class="mt-5" action="#" @submit.prevent="loadProducts">
                <div class="form-row">
                    <div class="col-lg-4 mt-2">
                        <label for="keywords">Keywords:</label>
                        <input
                            type="text"
                            v-model="form.keywords"
                            class="form-control"
                            id="keywords"
                            placeholder="keywords"
                            name="keywords"
                            required
                        />
                        <has-error :form="form" field="keywords"></has-error>
                    </div>
                    <div class="col-lg-4 mt-2">
                        <label for="price_min">Price:</label>
                        <div class="form-row">
                            <div class="col">
                                <input
                                    id="price_min"
                                    v-model="form.price_min"
                                    type="number"
                                    class="form-control mb-0"
                                    placeholder="$ Min"
                                />
                                <has-error
                                    :form="form"
                                    field="price_min"
                                ></has-error>
                            </div>

                            <div class="col">
                                <input
                                    id="price_max"
                                    v-model="form.price_max"
                                    type="number"
                                    class="form-control mb-0"
                                    placeholder="$ Max"
                                />
                                <has-error
                                    :form="form"
                                    field="price_max"
                                ></has-error>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 mt-2">
                        <label for="sort">Sort list:</label>
                        <select
                            class="form-control"
                            id="sort"
                            name="sort"
                            v-model="form.sort"
                        >
                            <option selected="selected">Default</option>
                            <option>Price Lowest First</option>
                        </select>
                        <has-error :form="form" field="sort"></has-error>
                    </div>

                    <div class="col-lg-2 mt-2">
                        <label>&nbsp;</label>
                        <input
                            type="submit"
                            class="form-control btn btn-primary"
                            id="submit"
                            value="submit"
                        />
                    </div>
                </div>
            </form>

            <div class="row mt-4">
                <product
                    v-for="product in products"
                    :key="product.id"
                    :product="product"
                ></product>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: function() {
        return {
            products: [],
            loading: true,
            form: new Form({
                keywords: "",
                price_max: "",
                price_min: "",
                sort: "Default"
            })
        };
    },

    mounted() {
        this.loading = false;
    },

    methods: {
        loadProducts: function() {
            this.loading = true;
            axios
                .post(process.env.MIX_API_URL + "/api/products", {
                    params: {
                        keywords: this.form.keywords,
                        MaxPrice: this.form.price_max,
                        MinPrice: this.form.price_min,
                        sort: this.form.sort
                    }
                })
                .then(response => {
                    if (response.data.error) {
                        alert(response.data.error);
                    } else {
                        this.products = response.data;
                    }

                    this.loading = false;
                })
                .catch(function(error) {
                    console.log(error);
                });
        }
    }
};
</script>
