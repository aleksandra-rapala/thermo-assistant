<?php
class OffersService {
    private $subscriptionRepository;

    public function __construct($subscriptionRepository) {
        $this->subscriptionRepository = $subscriptionRepository;
    }

    public function dispatchNotification($community, $message, $category) {
        $subscribers = $this->subscriptionRepository->findSubscribersByCommunityAndSubscriptionName($community, $category . "-email");

        foreach ($subscribers as $subscriber) {
            mail($subscriber, "Nowe oferty [thermo-assistant]", $message, "From: oferty@thermo-assistant");
        }
    }
}
