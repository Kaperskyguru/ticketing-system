import { required, email } from "vee-validate/dist/rules";
import { extend } from "vee-validate";

extend("required", {
    ...required,
    message: "The {_field_} is required"
});

extend("email", {
    ...email,
    message: "The {_field_} field must be a valid email"
});
