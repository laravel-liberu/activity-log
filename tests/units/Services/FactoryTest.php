<?php

namespace LaravelLiberu\ActivityLog\Test\units\Services;

use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use LaravelLiberu\ActivityLog\Contracts\Loggable;
use LaravelLiberu\ActivityLog\Contracts\ProvidesAttributes;
use LaravelLiberu\ActivityLog\Facades\Logger;
use LaravelLiberu\ActivityLog\Models\ActivityLog;
use LaravelLiberu\ActivityLog\Services\Factory;
use LaravelLiberu\People\Models\Person;
use LaravelLiberu\Users\Models\User;
use Tests\TestCase;

class FactoryTest extends TestCase
{
    use RefreshDatabase;

    private $faker;
    private $testModel;
    private $user;
    private $loggable;
    private $label;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = FakerFactory::create();

        TestModel::createTable();
        RelationalModel::createTable();

        $this->testModel = $this->createTestModel();

        $this->testModel->relationalModel()
            ->associate($this->createRelationalModel())
            ->save();

        $this->initUser();
    }

    /** @test */
    public function can_create_log()
    {
        $this->loggable = new LoggableStub($this->testModel);
        $this->label = 'name';

        $this->handle();

        $log = ActivityLog::first();
        $this->assertEquals(get_class($this->testModel), $log->model_class);
        $this->assertEquals($this->testModel->id, $log->model_id);
        $this->assertEquals($this->loggable->message(), $log->meta->message);
        $this->assertEquals($this->user->person->name, $log->meta->attributes->user);
        $this->assertEquals('test model', $log->meta->attributes->model);
        $this->assertEquals($this->testModel->name, $log->meta->attributes->label);
    }

    /** @test */
    public function can_create_log_with_provided_attributes()
    {
        $this->loggable = new LoggableWithAttributesStub($this->testModel);

        $this->handle();

        $this->assertEquals(
            $this->loggable->attributes()['attr'],
            ActivityLog::first()->meta->attributes->attr
        );
    }

    /** @test */
    public function can_create_log_with_relation_label()
    {
        $this->loggable = new LoggableStub($this->testModel);
        $this->label = 'relationalModel.name';

        $this->handle();

        $this->assertEquals(
            $this->testModel->relationalModel->name,
            ActivityLog::first()->meta->attributes->label
        );
    }

    private function handle()
    {
        Logger::register([TestModel::class => [
            'label' => $this->label,
        ]]);
        (new Factory($this->loggable))->create();
    }

    private function initUser()
    {
        $this->user = User::factory()->create();
        $this->user->person()->associate(Person::factory()->create());
        Auth::setUser($this->user);
    }

    private function createTestModel()
    {
        $test = new TestModel();
        $test->name = $this->faker->name;
        $test->save();

        return $test;
    }

    private function createRelationalModel()
    {
        $test = new RelationalModel();
        $test->name = $this->faker->name;
        $test->save();

        return $test;
    }
}

class TestModel extends Model
{
    protected $fillable = ['name'];

    public function relationalModel()
    {
        return $this->belongsTo(RelationalModel::class);
    }

    public static function createTable()
    {
        Schema::create('test_models', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('relational_model_id')->nullable();
            $table->string('name');
            $table->timestamps();
        });
    }
}

class RelationalModel extends Model
{
    protected $fillable = ['name'];

    public static function createTable()
    {
        Schema::create('relational_models', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
        });
    }
}

class LoggableStub implements Loggable
{
    public function __construct(private $testModel)
    {
    }

    public function type(): int
    {
        return 0;
    }

    public function model(): Model
    {
        return $this->testModel;
    }

    public function message(): string
    {
        return 'message';
    }

    public function icon(): string
    {
        return 'icon';
    }

    public function iconClass(): string
    {
        return 'icon';
    }
}

class LoggableWithAttributesStub extends LoggableStub implements ProvidesAttributes
{
    public function attributes(): array
    {
        return [
            'attr' => 'val',
        ];
    }
}
