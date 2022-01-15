<?php
class OffersService {
    private $subscriptionRepository;

    public function __construct($subscriptionRepository) {
        $this->subscriptionRepository = $subscriptionRepository;
    }

    public function dispatchNotification($community, $description, $category) {
        $subscribers = $this->subscriptionRepository->findSubscribersByCommunityAndSubscriptionName($community, $category . "-email");

        echo "Notification: $description\n";

        foreach ($subscribers as $subscriber) {
            echo "Sending to $subscriber\n";
        }
    }
}
