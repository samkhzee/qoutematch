import BuyerMasterLayout from '@/Components/Layout/BuyerMasterLayout';
import ProjectDetail from '@/Components/Shared/ProjectDetail';

export default function Detail({ pageTitle, project, canReport, dispute, disputeDetailRoute, reviewDimensions, disputeTypes }) {
    return (
        <BuyerMasterLayout pageTitle={pageTitle}>
            <ProjectDetail
                project={project}
                role="buyer"
                canReport={canReport}
                dispute={dispute}
                disputeDetailRoute={disputeDetailRoute}
                reviewDimensions={reviewDimensions}
                disputeTypes={disputeTypes}
            />
        </BuyerMasterLayout>
    );
}
