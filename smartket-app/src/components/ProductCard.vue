<template>
  <div class="product-card">
    <img :src="product.image_url || '/placeholder.png'" :alt="product.name" class="product-image" />
    <div class="product-info">
      <h3 class="product-name">{{ product.name }}</h3>
      <p class="product-price">{{ currency(product.price) }}</p>
    </div>
    <button @click="$emit('add-to-order', product)" class="add-button">Agregar</button>
  </div>
</template>

<script setup>
defineProps({
  product: {
    type: Object,
    required: true
  }
});

defineEmits(['add-to-order']);

function currency(value) {
  return new Intl.NumberFormat('es-PE', { style: 'currency', currency: 'PEN' }).format(value || 0);
}
</script>

<style scoped>
.product-card {
  border: 1px solid #e5e7eb;
  border-radius: 0.75rem;
  padding: 1rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
}
.product-image {
  width: 100px;
  height: 100px;
  object-fit: cover;
  margin-bottom: 0.75rem;
}
.product-name {
  font-weight: 600;
}
.product-price {
  color: #4f46e5;
}
.add-button {
  margin-top: 0.75rem;
  background-color: #4f46e5;
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 0.5rem;
  cursor: pointer;
}
</style>
