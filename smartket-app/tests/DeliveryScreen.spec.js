import { mount } from '@vue/test-utils'
import DeliveryScreen from '@/views/polleria/DeliveryScreen.vue'

describe('DeliveryScreen.vue', () => {
  it('muestra título y controles', () => {
    const wrapper = mount(DeliveryScreen, { global: { stubs: ['router-link'] } })
    expect(wrapper.text()).toContain('Delivery')
    expect(wrapper.text()).toContain('Intervalo:')
  })
})

