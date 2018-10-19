import { GitProject } from "./git-project";

export interface Contribution {
    id?: number,
    number_of_tokens: number,
    calculation_utc_datetime: Date,
    commit_id: string,
    git_project: GitProject
};
