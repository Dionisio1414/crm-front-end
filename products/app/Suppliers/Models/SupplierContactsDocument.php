<?php

namespace App\Suppliers\Models;

use App\Directories\Models\IndividualsDocuments\DirectoryIndividualsDocumentsDetail;
use Illuminate\Database\Eloquent\Model;

class SupplierContactsDocument extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'supplier_contact_id',
        'is_personal_identity',
        'document_type_id',
        'document_number',
        'document_issued_date',
        'document_validity_date',
        'document_issued',
        'date_create_document',
        'archive'
    ];

    protected $hidden = ['supplier_contact_id', 'archive', 'created_at', 'updated_at', 'cells', 'counterparty_id', 'supplier_contact'];

    protected $casts = [
        'is_personal_identity' => 'boolean',
        'archive' => 'boolean',
    ];

    protected $with = ['cells'];

    protected $appends = ['counterparty_id'];

    public function cells()
    {
        return $this->hasMany(DirectoryIndividualsDocumentsDetail::class, 'supplier_contact_document_id', 'id');
    }

    public function supplier_contact()
    {
        return $this->hasMany(SupplierContact::class, 'id', 'supplier_contact_id');
    }

    public function getCounterpartyIdAttribute()
    {
        foreach ($this->supplier_contact as $supplier_contact)
        {
            return optional($supplier_contact->counterparty)->id;
        }

        return null;
    }
}
