<?php

namespace Harsh\Slug;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;

trait Slugable
{
    public static $errors;
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
            if (! $model->validateSlugableFieldsExists()) {
                throw SlugableException::slugFieldNotFound(self::$error);
            }

            $model->runSlugable();
        });
    }

    /**
     * Validate that the calling model is correctly setup for cascading soft deletes.
     */
    protected function validateSlugableFieldsExists()
    {
        $fillable = $model->fillable();
        $slugables = $this->slugable;
        if ($slugables) {
            $error_message = "";
            foreach ($slugables as $slug_key => $slug_value) {
                if (in_array($slug_key, $fillable)) {
                    $error_message = get_called_class() . " fillables does not contain " . $slug_key . "!";
                    break 1;
                }
                if (in_array($slug_value, $fillable)) {
                    $error_message = $slug_key . " key not exist in fillables!";
                    break 1;
                }
            }
            if ($error_message) {
                self::$error = $error_message;
                return false;
            }
        }
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
        }
    }

    public function generateSlug($string)
	{
        return Str::slug($string, '-');
	}
}
