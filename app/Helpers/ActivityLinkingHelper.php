<?php

namespace App\Helpers;

use App\Models\WorkplanNaspLinkModel;
use App\Models\WorkplanMtdpLinkModel;
use App\Models\WorkplanCorporatePlanLinkModel;
use App\Models\WorkplanOthersLinkModel;

/**
 * Helper class for checking activity linking status
 * Used to validate if activities are properly linked before assignment
 */
class ActivityLinkingHelper
{
    /**
     * Check if an activity has any plan links (NASP, MTDP, Corporate, or Others)
     *
     * @param int $activityId
     * @return bool
     */
    public static function isActivityLinked($activityId)
    {
        // Check NASP links
        $naspModel = new WorkplanNaspLinkModel();
        $naspCount = $naspModel
            ->where('workplan_activity_id', $activityId)
            ->where('deleted_at IS NULL')
            ->countAllResults();

        if ($naspCount > 0) {
            return true;
        }

        // Check MTDP links
        $mtdpModel = new WorkplanMtdpLinkModel();
        $mtdpCount = $mtdpModel
            ->where('workplan_activity_id', $activityId)
            ->where('deleted_at IS NULL')
            ->countAllResults();

        if ($mtdpCount > 0) {
            return true;
        }

        // Check Corporate Plan links
        $corporateModel = new WorkplanCorporatePlanLinkModel();
        $corporateCount = $corporateModel
            ->where('workplan_activity_id', $activityId)
            ->where('deleted_at IS NULL')
            ->countAllResults();

        if ($corporateCount > 0) {
            return true;
        }

        // Check Others links
        $othersModel = new WorkplanOthersLinkModel();
        $othersCount = $othersModel
            ->where('workplan_activity_id', $activityId)
            ->where('deleted_at IS NULL')
            ->countAllResults();

        if ($othersCount > 0) {
            return true;
        }

        return false;
    }

    /**
     * Get detailed linking status for an activity
     *
     * @param int $activityId
     * @return array
     */
    public static function getActivityLinkingStatus($activityId)
    {
        $status = [
            'is_linked' => false,
            'nasp_linked' => false,
            'mtdp_linked' => false,
            'corporate_linked' => false,
            'others_linked' => false,
            'total_links' => 0,
            'link_details' => []
        ];

        // Check NASP links
        $naspModel = new WorkplanNaspLinkModel();
        $naspCount = $naspModel
            ->where('workplan_activity_id', $activityId)
            ->where('deleted_at IS NULL')
            ->countAllResults();

        if ($naspCount > 0) {
            $status['nasp_linked'] = true;
            $status['total_links']++;
            $status['link_details'][] = "NASP Plan ($naspCount links)";
        }

        // Check MTDP links
        $mtdpModel = new WorkplanMtdpLinkModel();
        $mtdpCount = $mtdpModel
            ->where('workplan_activity_id', $activityId)
            ->where('deleted_at IS NULL')
            ->countAllResults();

        if ($mtdpCount > 0) {
            $status['mtdp_linked'] = true;
            $status['total_links']++;
            $status['link_details'][] = "MTDP Plan ($mtdpCount links)";
        }

        // Check Corporate Plan links
        $corporateModel = new WorkplanCorporatePlanLinkModel();
        $corporateCount = $corporateModel
            ->where('workplan_activity_id', $activityId)
            ->where('deleted_at IS NULL')
            ->countAllResults();

        if ($corporateCount > 0) {
            $status['corporate_linked'] = true;
            $status['total_links']++;
            $status['link_details'][] = "Corporate Plan ($corporateCount links)";
        }

        // Check Others links
        $othersModel = new WorkplanOthersLinkModel();
        $othersCount = $othersModel
            ->where('workplan_activity_id', $activityId)
            ->where('deleted_at IS NULL')
            ->countAllResults();

        if ($othersCount > 0) {
            $status['others_linked'] = true;
            $status['total_links']++;
            $status['link_details'][] = "Others Links ($othersCount links)";
        }

        $status['is_linked'] = $status['total_links'] > 0;

        return $status;
    }

    /**
     * Get validation error message for unlinked activity
     *
     * @param string $activityTitle
     * @return string
     */
    public static function getUnlinkedActivityMessage($activityTitle)
    {
        return "The activity '{$activityTitle}' cannot be assigned because it is not linked to any plan. " .
               "Please link this activity to at least one plan (NASP, MTDP, Corporate, or Others) before creating a proposal.";
    }
}
