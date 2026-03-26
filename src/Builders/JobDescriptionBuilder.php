<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\Builders;

use JustSteveKing\Resume\DataObjects\JobDescription;

final class JobDescriptionBuilder
{
    private string $name = '';
    private ?string $location = null;
    private ?string $description = null;
    /** @var list<string> $highlights */
    private array $highlights = [];
    /** @var list<string> $skills */
    private array $skills = [];
    /** @var list<string> $tools */
    private array $tools = [];
    /** @var list<string> $responsibilities */
    private array $responsibilities = [];
    /** @var list<string> $deliverables */
    private array $deliverables = [];

    /**
     * Create a new JobDescriptionBuilder instance.
     *
     * @param string $name The name of the job description.
     */
    public function name(string $name): JobDescriptionBuilder
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set the location for the job description.
     *
     * @param string|null $location The location of the job.
     * @return JobDescriptionBuilder
     */
    public function location(?string $location): JobDescriptionBuilder
    {
        $this->location = $location;
        return $this;
    }

    /**
     * Set the description for the job description.
     *
     * @param string|null $description A brief description of the job.
     * @return JobDescriptionBuilder
     */
    public function description(?string $description): JobDescriptionBuilder
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Add a highlight to the job description.
     *
     * @param string $highlight A key highlight of the job.
     * @return JobDescriptionBuilder
     */
    public function addHighlight(string $highlight): JobDescriptionBuilder
    {
        $this->highlights[] = $highlight;
        return $this;
    }

    /**
     * Set the highlights for the job description.
     *
     * @param list<string> $highlights
     * @return JobDescriptionBuilder
     */
    public function highlights(array $highlights): JobDescriptionBuilder
    {
        $this->highlights = $highlights;
        return $this;
    }

    /**
     * Add a skill to the job description.
     *
     * @param string $skill A required skill for the job.
     * @return JobDescriptionBuilder
     */
    public function addSkill(string $skill): JobDescriptionBuilder
    {
        $this->skills[] = $skill;
        return $this;
    }

    /**
     * Set the skills for the job description.
     *
     * @param list<string> $skills
     * @return JobDescriptionBuilder
     */
    public function skills(array $skills): JobDescriptionBuilder
    {
        $this->skills = $skills;
        return $this;
    }

    /**
     * Add a tool to the job description.
     *
     * @param string $tool A tool used in the job.
     * @return JobDescriptionBuilder
     */
    public function addTool(string $tool): JobDescriptionBuilder
    {
        $this->tools[] = $tool;
        return $this;
    }

    /**
     * Set the tools for the job description.
     *
     * @param list<string> $tools
     * @return JobDescriptionBuilder
     */
    public function tools(array $tools): JobDescriptionBuilder
    {
        $this->tools = $tools;
        return $this;
    }

    /**
     * Add a responsibility to the job description.
     *
     * @param string $responsibility A responsibility associated with the job.
     * @return JobDescriptionBuilder
     */
    public function addResponsibility(string $responsibility): JobDescriptionBuilder
    {
        $this->responsibilities[] = $responsibility;
        return $this;
    }

    /**
     * Set the responsibilities for the job description.
     *
     * @param list<string> $responsibilities
     * @return JobDescriptionBuilder
     */
    public function responsibilities(array $responsibilities): JobDescriptionBuilder
    {
        $this->responsibilities = $responsibilities;
        return $this;
    }

    /**
     * Add a deliverable to the job description.
     *
     * @param string $deliverable An expected deliverable from the job.
     * @return JobDescriptionBuilder
     */
    public function addDeliverable(string $deliverable): JobDescriptionBuilder
    {
        $this->deliverables[] = $deliverable;
        return $this;
    }

    /**
     * Set the deliverables for the job description.
     *
     * @param list<string> $deliverables
     * @return JobDescriptionBuilder
     */
    public function deliverables(array $deliverables): JobDescriptionBuilder
    {
        $this->deliverables = $deliverables;
        return $this;
    }

    /**
     * Build and return a JobDescription instance.
     *
     * @return JobDescription
     */
    public function build(): JobDescription
    {
        return new JobDescription(
            name: $this->name,
            location: $this->location,
            description: $this->description,
            highlights: $this->highlights,
            skills: $this->skills,
            tools: $this->tools,
            responsibilities: $this->responsibilities,
            deliverables: $this->deliverables,
        );
    }
}
