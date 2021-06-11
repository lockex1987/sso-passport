// Đăng ký các component hay dùng
import DatePicker from './DatePicker.vue';
import DateRangePicker from './DateRangePicker.vue';
import DateRangePickerSeparate from './DateRangePickerSeparate.vue';
import WeekPicker from './WeekPicker.vue';
import MonthPicker from './MonthPicker.vue';
import YearPicker from './YearPicker.vue';
import TopHeader from './TopHeader.vue';
import DropdownSelect from './DropdownSelect.vue';
// import TagsInput from './TagsInput.vue';

Vue.component('date-picker', DatePicker);
Vue.component('date-range-picker', DateRangePicker);
Vue.component('date-range-picker-separate', DateRangePickerSeparate);
Vue.component('week-picker', WeekPicker);
Vue.component('month-picker', MonthPicker);
Vue.component('year-picker', YearPicker);
Vue.component('top-header', TopHeader);
Vue.component('dropdown-select', DropdownSelect);
// Vue.component('tags-input', TagsInput);
