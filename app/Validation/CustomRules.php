<?php

namespace App\Validation;

class CustomRules
{

    // USER RULES
    public function valid_data($data = '', string &$error = null)
    {
        $data = trim($data);
        $regex_special = '/[!#$%^&*?()\-_=+{};:,<>§"~]/';

        if (preg_match_all($regex_special, $data) > 0) {
            $this->form_validation->set_message('valid_data', 'Password must not have a special character.' . ' ' . htmlentities('!#$%^&*?()\-_=+{};:,<>§"~'));

            return FALSE;
        }
    }

    public function valid_password($password = '', string &$error = null)
    {
        $password = trim($password);

        $regex_lowercase = '/[a-z]/';
        $regex_uppercase = '/[A-Z]/';
        $regex_number = '/[0-9]/';
        $regex_special = '/[!@#$%^&*()\-_=+{};:,<.>§~]/';

        if (empty($password)) {
            $error = 'Password is required.';

            return FALSE;
        }

        if (preg_match_all($regex_lowercase, $password) < 1) {
            $error = 'Password must be at least one lowercase letter.';

            return FALSE;
        }

        if (preg_match_all($regex_uppercase, $password) < 1) {
            $error = 'Password must be at least one uppercase letter.';

            return FALSE;
        }

        if (preg_match_all($regex_number, $password) < 1) {
            $error = 'Password must have at least one number.';

            return FALSE;
        }

        if (preg_match_all($regex_special, $password) < 1) {
            $error = 'Password must have at least one special character.' . ' ' . htmlentities('!@#$%^&*()\-_=+{};:,<.>§~');

            return FALSE;
        }

        return TRUE;
    }
    // END USER RULES
}
