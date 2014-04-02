<?php

namespace mediasilo\project;

use mediasilo\http\WebClient;
use mediasilo\http\MediaSiloResourcePaths;
use mediasilo\project\Project;

class ProjectProxy {

    private $webClient;

    public function __construct($webClient) {
        $this->webClient = $webClient;
    }

    /**
     * Creates a brand spankin new project
     * @param Project $project
     */
    public function createProject(Project $project)
    {
        $result = json_decode($this->webClient->post(MediaSiloResourcePaths::PROJECTS, $project->toJson()));
        $project->id = $result->id;
    }

    /**
     * Gets an exiting project given a project Id
     * @param $id
     * @return Project
     */
    public function getProject($id)
    {
        $clientResponse = $this->webClient->get(MediaSiloResourcePaths::PROJECTS . "/" . $id);
        return Project::fromJson($clientResponse->getBody());
    }

    /**
     * Updates an existing project. Use this if you want to change the name or description of a project.
     * You won't be able to change the project owner here, though.
     * @param Project $project
     */
    public function updateProject(Project $project)
    {
        $this->webClient->put(MediaSiloResourcePaths::PROJECTS, $project->toJson());
    }

    /**
     * Sayonara project
     * @param $id
     * @return mixed
     */
    public function deleteProject($id) {
        return $this->webClient->delete(MediaSiloResourcePaths::PROJECTS . "/" . $id);
    }

    /**
     * Have a project that has the structure that you need. Use this function to clone it.
     * You can also clone users and roles within a project.
     * @param $projectId
     * @return mixed
     */
    public function cloneProject($projectId)
    {
        $resourcePath = sprintf(MediaSiloResourcePaths::CLONE_PROJECTS, $projectId);
        return json_decode($this->webClient->post($resourcePath, null));
    }

    /**
     * Want to see all of the projects a given user is working in? Use this function.
     * @param $userId
     * @return mixed
     */
    public function getUsersProjects($userId)
    {
        $resourcePath = sprintf(MediaSiloResourcePaths::USERS_PROJECTS, $userId);
        $clientResponse = $this->webClient->get($resourcePath);
        return json_decode($clientResponse->getBody());
    }
}