<template>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card h-100">
            <a :href="product.click_out_link" target="_blank">
                <img
                    class="card-img-top"
                    :src="
                        product.main_photo_url
                            ? product.main_photo_url
                            : 'http://placehold.it/400x400'
                    "
                    alt=""
                />
            </a>
            <div class="card-body">
                <h6 class="card-title">
                    <a :href="product.click_out_link" target="_blank">{{
                        product.title
                    }}</a>
                </h6>
                <h6>Price: {{ product.price_currency }} {{ product.price }}</h6>
                <h6>Shipment Price: {{ product.price }}</h6>
                <h6>Valid Until: {{ product.valid_until }}</h6>
                <h6>Provider: {{ product.provider }}</h6>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["product"],

    methods: {
        limitDescription: function(string, length) {
            return string.length > length
                ? string.substring(0, length) + "..."
                : string;
        }
    }
};
</script>
<style scoped>
.card-img-top {
    width: auto;
    display: block;
    margin-left: auto;
    margin-right: auto;
    height: 130px;
}
</style>
