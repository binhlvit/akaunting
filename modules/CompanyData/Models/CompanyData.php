<?php

namespace Modules\CompanyData\Models;

use App\Abstracts\Model;
use App\Traits\Tenants;
use Bkwld\Cloner\Cloneable;
use Kyslik\ColumnSortable\Sortable;

class CompanyData extends Model
{
    use Cloneable, Sortable, Tenants;

    protected $table = 'company_data';

    protected $tenantable = false;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'company_name',
        'company_name_acronym',
        'company_name_en',
        'address',
        'phone',
        'company_type',
        'company_create_date',
        'company_status',
        'representative',
        'note',
        'type',
        'date_of_incorporation'
    ];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['created_at','company_name', 'phone', 'representative'];

    /**
     * Scope to get all rows filtered, sorted and paginated.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $sort
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCollect($query, $sort = 'created_at')
    {
        $request = request();

        $search = $request->get('search');
        $limit = $request->get('limit', setting('default.list_limit', '25'));

        return $query->usingSearchString($search)->sortable($sort)->paginate($limit);
    }
}
