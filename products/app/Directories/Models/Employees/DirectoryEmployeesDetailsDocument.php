<?php

namespace App\Directories\Models\Employees;

use App\Directories\Models\EmployeeDocuments\DirectoryEmployeeDocumentsDetail;
use Illuminate\Database\Eloquent\Model;

class DirectoryEmployeesDetailsDocument extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'directory_employee_id',
        'is_personal_identity',
        'document_type_id',
        'document_number',
        'document_issued_date',
        'document_validity_date',
        'document_issued',
        'date_create_document',
        'is_default',
        'archive'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['directory_employee_id', 'archive', 'created_at', 'updated_at', 'cells', 'is_default'];

    protected $casts = [
        'is_personal_identity' => 'boolean',
        'archive' => 'boolean',
        'is_default' => 'boolean'
    ];

    protected $with = ['cells'];

    public function cells()
    {
        return $this->hasMany(DirectoryEmployeeDocumentsDetail::class, 'employee_document_id', 'id');
    }

    public function employee()
    {
        return $this->hasOne(DirectoryEmployee::class, 'id', 'directory_employee_id')
                    ->without(['cells', 'main', 'documents']);
    }
}
