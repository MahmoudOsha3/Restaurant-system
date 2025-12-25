import { ref, computed } from 'vue';

export function useCashier() {
    const cart = ref([]);
    const meals = ref([
        { id: 1, name: 'ساندوتش كفتة', price: 65 },
    ]);

    const addToCart = (m) => {
        const item = cart.value.find(i => i.id === m.id);
        item ? item.qty++ : cart.value.push({...m, qty:1});
    };

    const totalPrice = computed(() =>
        cart.value.reduce((s, i) => s + i.price * i.qty, 0)
    );

    return { cart, meals, addToCart, totalPrice };
}
