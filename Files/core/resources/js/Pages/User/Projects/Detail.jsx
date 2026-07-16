import MasterLayout from '@/Components/Layout/MasterLayout';
import ProjectDetail from '@/Components/Shared/ProjectDetail';

export default function Detail({ pageTitle, project, canReport, dispute, disputeDetailRoute, disputeTypes }) {
    return (
        <MasterLayout pageTitle={pageTitle}>
            <ProjectDetail
                project={project}
                role="freelancer"
                canReport={canReport}
                dispute={dispute}
                disputeDetailRoute={disputeDetailRoute}
                disputeTypes={disputeTypes}
            />
        </MasterLayout>
    );
}
