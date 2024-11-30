<?php

namespace App\Models;

enum RoleCode: string {
    case Admin = 'admin';
    case Student = 'student';
    case Teacher = 'teacher';
}
