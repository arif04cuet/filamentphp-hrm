<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BanglaDate;
use Illuminate\Database\Eloquent\SoftDeletes;

class Holiday extends Model
{
    use HasFactory, SoftDeletes;

    const A_GENERAL_HOLIDAYS = 'GENERAL HOLIDAYS';
    const B_GOVT_HOLIDAYS_BY_EXECUTIVE_ORDER = 'GOVT HOLIDAYS BY EXECUTIVE ORDER';
    const C_MUSLIM_HOLIDAYS = 'MUSLIM HOLIDAYS';
    const D_HINDU_HOLIDAYS = 'HINDU HOLIDAYS';
    const E_CHRISTIAN_HOLIDAYS = 'CHRISTIAN HOLIDAYS';
    const F_BUDDHA_HOLIDAYS = 'BUDDHA HOLIDAYS';
    const G_ETHNIC_HOLIDAYS = 'ETHNIC HOLIDAYS';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'holidays';

    /**
     * The name that will be used when log this model. (optional)
     *
     * @var bool
     */
    protected static $logName = 'holidays';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['type', 'holiday_name_id', 'from', 'to', 'date_from', 'date_to', 'date_bn', 'count', 'flag'];

    public function holidayName()
    {
        return $this->belongsTo(HolidayName::class);
    }

    public static function type($key = null)
    {
        $holidays = [
            self::A_GENERAL_HOLIDAYS => 'GENERAL HOLIDAYS',
            self::B_GOVT_HOLIDAYS_BY_EXECUTIVE_ORDER => 'GOVT HOLIDAYS BY EXECUTIVE ORDER',
            self::C_MUSLIM_HOLIDAYS => 'MUSLIM HOLIDAYS',
            self::D_HINDU_HOLIDAYS => 'HINDU HOLIDAYS',
            self::E_CHRISTIAN_HOLIDAYS => 'CHRISTIAN HOLIDAYS',
            self::F_BUDDHA_HOLIDAYS => 'BUDDHA HOLIDAYS',
            self::G_ETHNIC_HOLIDAYS => 'ETHNIC HOLIDAYS',
        ];

        if (!is_null($key) && isset($holidays[$key]))
            return $holidays[$key];

        return $holidays;
    }

    //Converting English Date To Bangla Date
    public static function dateBn($date)
    {
        $bn_date = new BanglaDate(strtotime($date), 0);
        $bndate = $bn_date->get_date();
        return $bndate;
    }

    public static function getDataByType($name)
    {
        return Holiday::where('type', $name)->get();
    }

    public static function getDataByYear($year)
    {
        return Holiday::whereYear('date_from', $year)->get();
    }

    public static function getDataByTypeAndYear($name, $year)
    {
        return Holiday::where('type', $name)->whereYear('date_from', $year)->orderBy('date_from', 'ASC')->get();
    }

    public static function getFlagById($id)
    {
        $data = Holiday::where('id', $id)->first();
        if ($data->flag == 'Yes') {
            return "*";
        }
    }
}