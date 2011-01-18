using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using System.IO;

namespace xDiffPatcher
{
    public partial class frmProfiles : Form
    {
        public List<DiffProfile> Profiles = new List<DiffProfile>();

        public frmProfiles()
        {
            InitializeComponent();
        }

        private void frmProfiles_Load(object sender, EventArgs e)
        {

        }

        private void lstProfiles_SelectedIndexChanged(object sender, EventArgs e)
        {
            if (lstProfiles.SelectedIndex < 0 || lstProfiles.SelectedIndex >= Profiles.Count)
            {
                txtPatches.Text = "";
                grpProfile.Enabled = false;
                grpProfile.Text = "";
                return;
            }

            grpProfile.Enabled = true;

            DiffProfile p = Profiles[lstProfiles.SelectedIndex];
            grpProfile.Text = p.Name;
            txtPatches.Text = "";

            foreach (DiffProfileEntry entry in p.Entries)
            {
                string str = "* " + entry.PatchName;
                
                if (entry.Inputs.Count > 0)
                {
                    str += " (";
                    int num = 0;
                    string[] inputs = new string[entry.Inputs.Count];
                    foreach (DiffProfileInput i in entry.Inputs)
                        inputs[num++] = "$" + i.name + "="+ i.value;
                    
                    str += string.Join(", ", inputs) + ")";
                }

                txtPatches.Text += str + "\r\n";
            }
        }

        private void frmProfiles_Shown(object sender, EventArgs e)
        {
            lstProfiles.Items.Clear();

            if (!Directory.Exists("profiles"))
                return;

            DirectoryInfo dir = new DirectoryInfo("profiles");
            FileInfo[] files = dir.GetFiles("*.xml", SearchOption.TopDirectoryOnly);

            foreach (FileInfo f in files)
            {
                try
                {
                    DiffProfile profile = DiffProfile.Load(f.FullName);
                    if (profile == null)
                        continue;

                    Profiles.Add(profile);
                    if (f.Name.Replace(".xml", "") == profile.Name)
                        lstProfiles.Items.Add(profile.Name);
                    else
                        lstProfiles.Items.Add(profile.Name + "(" + f.Name + ")");
                }
                catch (Exception)
                {
                    continue;
                }
            }

            lstProfiles_SelectedIndexChanged(null, null);
        }

        private void btnApply_Click(object sender, EventArgs e)
        {
            if (lstProfiles.SelectedIndex < 0 || lstProfiles.SelectedIndex >= Profiles.Count)
                return;

            DiffProfile p = Profiles[lstProfiles.SelectedIndex];

            p.Apply(ref ((frmMain)this.Owner).lstPatches, ref ((frmMain)this.Owner).file);
            this.Close();
        }

        private void btnDelete_Click(object sender, EventArgs e)
        {
            if (lstProfiles.SelectedIndex < 0 || lstProfiles.SelectedIndex >= Profiles.Count)
                return;

            DiffProfile p = Profiles[lstProfiles.SelectedIndex];

            if (MessageBox.Show("Do you really want to delete '" + p.Name + "' ?", "Warning", MessageBoxButtons.YesNo) != System.Windows.Forms.DialogResult.Yes)
                return;

            if (p.FullPath != null)
                File.Delete(p.FullPath);

            Profiles.Remove(p);
            lstProfiles.Items.RemoveAt(lstProfiles.SelectedIndex);     
        }
    }
}
