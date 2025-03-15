<?php
namespace App\Models;

enum RoleCode: string {
    case Admin               = 'admin';
    case Student             = 'student';
    case Teacher             = 'teacher';
    case Registrar           = 'registrar';
    case HumanResourcesStaff = 'human_resources_staff';
    case Dean                = 'dean';
}
