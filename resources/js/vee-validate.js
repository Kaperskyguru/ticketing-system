import { required, email } from 'vee-validate/dist/rules';
// import en from 'vee-validate/dist/locale/en';
import {
  extend
} from 'vee-validate';


extend('required', {
    ...required,
    message: 'The {_field_} is required'
});

extend('email', {
    ...email,
    message: 'The {_field_} field must be a valid email'
});


// for (let rule in rules) {
//   extend(rule, {
//     ...rules[rule],
//     message: en.messages[rule]
//   });
//}