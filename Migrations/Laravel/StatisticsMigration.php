<?php

namespace Tecnoready\Common\Migrations\Laravel;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migraciones base para estadisticas en laravel
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
final class StatisticsMigration extends Migration {
    public static function createTableYear(Blueprint $t) {
        $t->increments('id');
        $t->integer('year');
        $t->decimal("total",30,3);
        for($month = 1 ; $month <= 12 ; $month++){
            $property = sprintf("total_month_%s",$month);
            $t->decimal($property,30,3);
        }
        
        $t->string('object', 100)->nullable();
        $t->string('created_from_ip', 45);
        $t->string('object_id', 30);
        $t->string('object_type', 30);
        $t->longText('description')->nullable();
        $t->longText('user_agent');
        
        $t->timestamp('created_at');
        $t->timestamp('updated_at');

        $t->engine = "InnoDB";
    }
    
    public static function createTableMonth($tableNameYear,Blueprint $t) {
        $t->increments('id');
        $t->integer('year');
        $t->integer('month');
        $t->decimal("total",30,3);
        
        for($day = 1 ; $day <= 31 ; $day++){
            $property = sprintf("day%s",$day);
            $t->decimal($property,30,3);
        }
        
        $t->string('object', 100)->nullable();
        $t->string('created_from_ip', 45);
        $t->string('object_id', 30);
        $t->string('object_type', 30);
        $t->longText('description')->nullable();
        $t->longText('user_agent');
        
        $t->timestamp('created_at');
        $t->timestamp('updated_at');

        $t->integer('year_entity_id')->unsigned();
        $t->foreign('year_entity_id')->references('id')->on($tableNameYear);
        $t->engine = "InnoDB";
    }
}
