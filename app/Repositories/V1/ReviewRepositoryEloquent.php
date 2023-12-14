<?php

namespace App\Repositories\V1;

use App\Criteria\V1\SearchCriteria;
use App\Criteria\V1\Users\BannedUserCriteria;
use App\Enums\MediaCollections;
use App\Models\Review;
use App\Models\User;
use App\Repositories\V1\Admin\Support\TicketRepositoryEloquent;
use App\Traits\UploadAble;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Class ReviewRepositoryEloquent
 * @package App\Repositories\V1
 */
class ReviewRepositoryEloquent extends BaseRepository implements ReviewRepository {

    use UploadAble;

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'status',
        'user_id',
        'advertise_id',
        'user.banned',
        'author.banned',
        'published',
        'has_image',
        'advertise.is_fake',
        'advertise.user_id'
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string {
        return Review::class;
    }

    /**
     * Boot up the repository, pushing criteria
     * @throws RepositoryException|RepositoryException
     */
    public function boot() {
        $this->pushCriteria(app(BannedUserCriteria::class));

        $this->pushCriteria(app(SearchCriteria::class));
    }

    /**
     * @param array $attributes
     * @return Review
     * @throws \Throwable
     */
    public function addReview(array $attributes): Review {
        \DB::transaction(function () use(&$review, $attributes) {
            if (existsUploadAbleFileInArray($attributes)) {
                $attributes['has_image'] = true;
            }

            $review = $this->create($attributes);

            if (
                existsUploadAbleFileInArray($attributes)
                && $review instanceof Review
            ) $this->addPicturesIfExistsFile($review, $attributes);
        });

        return $review;
    }

    /**
     * @param array $attributes
     * @return Review
     * @throws \Throwable
     */
    public function updateReview(array $attributes): Review {
        \DB::transaction(function () use(&$review, $attributes) {
            if (existsUploadAbleFileInArray($attributes)) {
                $attributes['has_image'] = true;
            }

            $review = $this->update($attributes, $attributes['id']);

            if (existsUploadAbleFileInArray($attributes)) {
                $this->addPicturesIfExistsFile($review, $attributes);
            }
        });

        return $review;
    }

    /**
     * @param User $user
     * @param Review $review
     * @param array $attributes
     * @return void
     * @throws \Throwable
     */
    public function addComplaint(User $user, Review $review, array $attributes): void {
        \DB::transaction(function () use ($attributes, &$review, $user, &$complaint) {
            $attributes['user_id'] = $user->id;

            $complaint = $review->complaint()->create($attributes);

            if (existsUploadAbleFileInArray($attributes))
                foreach ($attributes['files'] as $file)
                    $this->upload($complaint, $file, MediaCollections::REVIEWS_COMPLAINT_FILE);

            $tickerData['user_id'] = $user?->id;
            $tickerData['review_id'] = $review?->id;
            $tickerData['description'] = $attributes['description'] ?? '';
            $tickerData['name'] = $user?->full_name;

            if ($user->email) $tickerData['email'] = $user->email;

            app(TicketRepositoryEloquent::class)->addTicket($tickerData);
        });
    }

    /**
     * @param Review $review
     * @param array $attributes
     * @return void
     */
    private function addPicturesIfExistsFile(Review $review, array $attributes): void {

        if (isset($attributes['pictures'])) {

            foreach ($attributes['pictures'] as $picture) {

                /**
                 * Добовляем Новую
                 */
                $this->addResponsiveImage($review, $picture, \App\Enums\MediaCollections::REVIEW_PICTURES);
            }
        }
    }
}
