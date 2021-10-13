<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;

trait Slugable
{
    /**
     * Boot the trait.
     *
     * Listen for the saving event of a soft deleting model, and run
     * the delete operation for any configured relationship methods.
     *
     * @throws \LogicException
     */
    protected static function bootSlugable()
    {
        static::saving(function ($model) {
            $model->validateSlugable();

            $model->runSlugable();
        });
    }

    /**
     * Validate that the calling model is correctly setup for cascading soft deletes.
     *
     * 
     */
    protected function validateSlugable()
    {
        return true;
    }

    /**
     * Run the slugable for this model.
     *
     * @return void
     */
    protected function runSlugable()
    {
        $slugables = $this->slugable;
        foreach ($slugables as $slug_to => $slug_by) {
            $this->{$slug_to} = $this->generateSlug($this->{$slug_by});
            info($this->{$slug_to});
        }
    }

    public function generateSlug($string)
	{
        return Str::slug($string, '-');
	}
}
