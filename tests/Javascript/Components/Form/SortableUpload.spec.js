import {beforeEach, expect, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream')

import {createLocalVue, mount, shallowMount} from '@vue/test-utils'
import {InertiaApp} from '@inertiajs/inertia-vue'
import {InertiaForm} from 'laravel-jetstream'
import SortableUpload from '@src/Components/Form/SortableUpload'

let localVue

beforeEach(() => {
    localVue = createLocalVue()
    localVue.use(InertiaApp)
    localVue.use(InertiaForm)

});

test('should mount without crashing', () => {
    const wrapper = shallowMount(SortableUpload, {localVue});

    expect(wrapper.text()).toBeDefined();
})

test('should add and remove styles', () => {
    const wrapper = shallowMount(SortableUpload, {localVue});

    expect(wrapper.vm.$refs.dnd.classList.contains('border-blue-400')).toBeFalsy()
    expect(wrapper.vm.$refs.dnd.classList.contains('ring-4')).toBeFalsy()
    expect(wrapper.vm.$refs.dnd.classList.contains('ring-inset')).toBeFalsy()

    wrapper.find('input').trigger('dragover');

    expect(wrapper.vm.$refs.dnd.classList.contains('border-blue-400')).toBeTruthy()
    expect(wrapper.vm.$refs.dnd.classList.contains('ring-4')).toBeTruthy()
    expect(wrapper.vm.$refs.dnd.classList.contains('ring-inset')).toBeTruthy()

    wrapper.find('input').trigger('drop');
    wrapper.find('input').trigger('dragleave');

    expect(wrapper.vm.$refs.dnd.classList.contains('border-blue-400')).toBeFalsy()
    expect(wrapper.vm.$refs.dnd.classList.contains('ring-4')).toBeFalsy()
    expect(wrapper.vm.$refs.dnd.classList.contains('ring-inset')).toBeFalsy()

})

test('should return human readable size', () => {
    const wrapper = shallowMount(SortableUpload, {localVue});

    wrapper.vm.humanFileSize(1);
    expect(wrapper.vm.humanFileSize(1024)).toBe("1 kB");
})

test('should cover drag', () => {
    const wrapper = shallowMount(SortableUpload, {localVue});

    wrapper.findAll('div').trigger('dragstart');
    wrapper.findAll('div').trigger('dragend');
    wrapper.findAll('div').trigger('dragenter');
    wrapper.findAll('div').trigger('dragleave');

    expect(wrapper.text()).toBeDefined();
})

/*
test('should change when remove', () => {
    const wrapper = shallowMount(SortableUpload, {localVue});
    // breaks when calling createFileList because it calls Clipboard
    wrapper.vm.remove(1);
    expect(wrapper.emitted().change).toBeTruthy();
})*/
