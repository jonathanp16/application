import {beforeEach, expect, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream')

import {createLocalVue, mount, shallowMount} from '@vue/test-utils'
import {InertiaApp} from '@inertiajs/inertia-vue'
import {InertiaForm} from 'laravel-jetstream'
import Component from '@src/Jetstream/Checkbox'
import regeneratorRuntime from "regenerator-runtime/runtime"

let localVue

beforeEach(() => {
    localVue = createLocalVue()
    localVue.use(InertiaApp)
    localVue.use(InertiaForm)

});

test('should mount without crashing', () => {
    const wrapper = shallowMount(Component, {localVue});

    expect(wrapper.text()).toBeDefined();
})

test('verify computed proxy field', async () => {
    const wrapper = shallowMount(Component, {localVue});

    expect(wrapper.emitted().change).toBeTruthy();

    expect(Component.computed.proxyChecked.set()).toBeCalled();
    await wrapper.find('input').trigger('click');

    expect(Component.computed.proxyChecked.get()).toBeCalled();
})