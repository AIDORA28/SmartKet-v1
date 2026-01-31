import { mount } from '@vue/test-utils'
import MeseroOrders from '@/views/polleria/MeseroOrders.vue'

describe('MeseroOrders.vue', () => {
  it('renderiza formulario básico', () => {
    const wrapper = mount(MeseroOrders, { global: { stubs: ['router-link'] } })
    expect(wrapper.text()).toContain('Toma de pedidos')
    const inputs = wrapper.findAll('input')
    expect(inputs.length).toBeGreaterThan(0)
  })
})

