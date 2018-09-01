<?php
namespace App\Models\TatucoBuilder;

use Illuminate\Http\Request;
use Illuminate\Database\Query\Builder; 
use App\Traits\DoWhereTrait;
class TatucoBuilder extends Builder
{
    use DoWhereTrait;
 /* protected $fields;
  protected $request;

  public function __construct(Builder $builder, Request $request = null)
  {
     parent::__construct(clone $builder->getQuery());

        //$this->initializeFromBuilder($builder);

        $this->request = $request ?? request();

        //$this->parseSelectedFields();

       /* if ($this->request->sorts()) {
            $this->allowedSorts('*');
        }
  }

   protected function initializeFromBuilder(Builder $builder)
    {
        $this->setModel($builder->getModel())
            ->setEagerLoads($builder->getEagerLoads());

        $builder->macro('getProtected', function (Builder $builder, string $property) {
            return $builder->{$property};
        });

        $this->scopes = $builder->getProtected('scopes');

        $this->localMacros = $builder->getProtected('localMacros');

        $this->onDelete = $builder->getProtected('onDelete');
    }

     protected function parseSelectedFields()
    {
        $this->fields = $this->request->fields();

        $modelFields = $this->fields->get(
            $this->getModel()->getTable()
        );

        if (! $modelFields) {
            return;
        }

        $this->select(explode(',', $modelFields));
    }*/
}