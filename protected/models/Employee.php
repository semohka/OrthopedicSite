<?php

/**
 * This is the model class for table "employees".
 *
 * The followings are the available columns in table 'employees':
 * @property integer $id
 * @property string $surname
 * @property string $name
 * @property string $patronymic
 * @property string $date_created
 * @property integer $is_deleted
 *
 * The followings are the available model relations:
 * @property Order[] $orders
 */
class Employee extends CActiveRecord
{
//    public $FIO;

    public function tableName()
    {
        return 'employees';
    }

    public function rules()
    {
        return [
            ['surname, name, patronymic', 'required'],
            ['surname, name, patronymic', 'length', 'max' => 30],
            ['is_deleted', 'numerical', 'integerOnly' => true],
            ['id, surname, name, patronymic, date_created, is_deleted', 'safe', 'on' => 'search'],
        ];
    }

    public function relations()
    {
        return [
            'orders' => [self::HAS_MANY, 'Order', 'employee_id'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => 'Модельер',
            'surname' => 'Фамилия модельера',
            'name' => 'Имя модельера',
            'patronymic' => 'Отчество модельера',
            'date_created' => 'Дата регистрации',
            'is_deleted' => 'Статус',
        ];
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function beforeSave()
    {
        if ($this->isNewRecord) {
            $this->date_created = new CDbExpression('NOW()');
        }

        return parent::beforeSave();
    }


    public function getEmployeeList($id = null)
    {
        if ($id === null) {
            $query = Employee::model()->findAllBySql("SELECT id, CONCAT_WS(' ', surname, name, patronymic) AS FIO FROM employees WHERE is_deleted = 0");
            $list = CHtml::listData($query, 'id', 'FIO');
            return $list;
        } else {
            $query = Employee::model()->findAllBySql("SELECT id, CONCAT_WS(' ', surname, name, patronymic) AS FIO
														FROM employees WHERE is_deleted=0 OR id='" . $id . "' ");
            $list = CHtml::listData($query, 'id', 'FIO');
            return $list;
        }
    }

    public static function getEmployeeShortcutList($employee_id)
    {
        $employee = Yii::app()->db->createCommand()
            ->select("CONCAT(id, ' ', LEFT(name, 1), '.', LEFT(patronymic, 1), '.') AS Employee")
            ->from('employees')
            ->where('id=:id', [':id' => $employee_id])
            ->queryRow();
        return $employee['Employee'];
    }

    public function fullName()
    {
        return $this->surname . ' ' . $this->name . ' ' . $this->patronymic;
    }

    public static function searchEmployee($is_deleted = false)
    {
        $result = self::model()->findAll([
            'select' => 'surname, name, patronymic',
            'condition' => 'is_deleted=:is_deleted',
            'params' => [':is_deleted' => $is_deleted ? 1 : 0],
        ]);

        $deleted = '';
        foreach ($result as $employee) {
            $deleted .= '<div>' . $employee->fullName() . '</div>';
        }

        return $deleted;
    }

}