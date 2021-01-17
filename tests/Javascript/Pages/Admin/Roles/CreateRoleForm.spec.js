import {afterEach, beforeEach, jest, test} from "@jest/globals";

jest.mock('laravel-jetstream')
import {createLocalVue, shallowMount} from '@vue/test-utils';
import {InertiaApp} from "@inertiajs/inertia-vue";
import {InertiaForm} from "laravel-jetstream";
import {InertiaFormMock} from "@test/__mocks__/laravel-jetstream";
import CreateRoleForm from "@src/Pages/Admin/Roles/CreateRoleForm";

let localVue;
let wrapper;

beforeEach(() => {
    localVue = createLocalVue()
    localVue.use(InertiaApp)
    localVue.use(InertiaForm)

    wrapper = shallowMount(CreateRoleForm, {localVue})
});
afterEach(() => {
    localVue = null
    wrapper = null

    InertiaFormMock.error.mockClear()
    InertiaFormMock.post.mockClear()
})

test('should mount without crashing', () => {
    expect(wrapper.text()).toBeDefined()
})

test('createRole when no form errors', () => {

    InertiaFormMock.post.mockReturnValueOnce({
        then(callback) {
            callback({})
        }
    })

    InertiaFormMock.hasErrors.mockReturnValueOnce(false)

    wrapper.vm.createRole()

    expect(InertiaFormMock.post).toBeCalledTimes(1)

})

test('createRole when form errors', () => {

    InertiaFormMock.post.mockReturnValueOnce({
        then(callback) {
            callback({})
        }
    })

    InertiaFormMock.hasErrors.mockReturnValueOnce(true)
    InertiaFormMock.error.mockReturnValueOnce("Some name error")

    wrapper.vm.createRole()

    expect(InertiaFormMock.post).toBeCalledTimes(1)

})
