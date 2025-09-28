namespace TechFixApplication.Models.Entities
{
    public class Staff
    {
        public Guid Id { get; set; }

        public required string Name { get; set; }

        public required string Email { get; set; }

        public string? Phone { get; set; }

        public required string Password { get; set; }
    }
}
