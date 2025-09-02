<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\BelongsToMany;
    use Illuminate\Database\Eloquent\Relations\HasMany;

    class Book extends Model
    {
        protected $fillable = [
            'google_id',
            'name',
            'description',
            'page_count',
            'unit_count',
            'isbn',
            'language',
            'binding',
            'script',
            'dimensions',
            'publisher_id',
        ];

        public function genres(): BelongsToMany
        {
            return $this->belongsToMany(Genre::class);
        }

        public function images(): HasMany
        {
            return $this->HasMany(Image::class);
        }

        public function categories(): belongsToMany
        {
            return $this->belongsToMany(Category::class);
        }

        public function publisher(): BelongsTo
        {
            return $this->belongsTo(Publisher::class);
        }

        public function authors(): BelongsToMany
        {
            return $this->belongsToMany(Author::class);
        }
    }
